<?php

namespace App\Model\Commands\Addons\Content;

use App\Core\Utils\Validators;
use App\Model\Commands\BaseCommand;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Github\Github;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateContentCommand extends BaseCommand
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var GithubService */
    private $github;

    /**
     * @param AddonRepository $addonRepository
     * @param GithubService $github
     */
    public function __construct(AddonRepository $addonRepository, GithubService $github)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->github = $github;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('addons:content:generate')
            ->setDescription('Generate addons contents');

        $this->addOption(
            'rest',
            NULL,
            InputOption::VALUE_NONE,
            'Should synchronize only queued addons?'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        // FILTER PACKAGES ===========================================

        if ($input->getOption('rest') == TRUE) {
            $addons = $addons->findBy(['this->github->contentHtml' => NULL]);
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            // Raw
            $response = $this->github->readme($addon->owner, $addon->name, 'raw');
            if ($response->isOk()) {
                // Content
                $addon->github->contentRaw = $response->getBody();
            } else {
                $addon->github->contentRaw = '';
                $output->writeln('Skip (content) [failed download raw content]: ' . $addon->fullname);
            }

            // HTML
            $response = $this->github->readme($addon->owner, $addon->name, 'html');
            if ($response->isOk()) {
                // Content
                $addon->github->contentHtml = $response->getBody();
                $this->reformatLinks($addon->github);
            } else {
                $addon->github->contentHtml = '';
                $output->writeln('Skip (content) [failed download html content]: ' . $addon->fullname);
            }

            // Persist
            if ($addon->github->isModified()) {
                $this->addonRepository->persistAndFlush($addon);
            }

            // Increase counting
            $counter++;
        }

        $output->writeln(sprintf('Updated %s addons contents', $counter));
    }

    /**
     * @param Github $github
     * @return void
     */
    protected function reformatLinks(Github $github)
    {
        // Resolve links
        $github->contentHtml = Strings::replace($github->contentHtml, '#href=\"(.*)\"#iU', function ($matches) use ($github) {
            list ($all, $url) = $matches;

            if (!Validators::isUrl($url)) {
                if (Validators::isUrlFragment($url)) {
                    $url = $github->linker->getFileUrl(NULL, $url);
                } else {
                    $url = $github->linker->getBlobUrl($url);
                }
            }

            return sprintf('href="%s"', $url);
        });

        // Resolve images
        $github->contentHtml = Strings::replace($github->contentHtml, '#img.+src=\"(.*)\"#iU', function ($matches) use ($github) {
            list ($all, $url) = $matches;

            if (!Validators::isUrl($url)) {
                $url = $github->linker->getRawUrl($url);
            }

            return sprintf('img src="%s"', $url);
        });
    }

}
