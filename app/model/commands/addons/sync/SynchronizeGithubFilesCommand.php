<?php

namespace App\Model\Commands\Addons\Sync;

use App\Model\Commands\BaseCommand;
use App\Model\Exceptions\RuntimeException;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebServices\Github\Service;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeGithubFilesCommand extends BaseCommand
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var Service */
    private $github;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $github
     */
    public function __construct(AddonRepository $addonRepository, Service $github)
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
            ->setName('app:addons:sync:github-files')
            ->setDescription('Synchronize github files (composer.json, bower.json)');

        $this->addArgument(
            'type',
            InputOption::VALUE_REQUIRED,
            'What type should be synchronized'
        );

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
        if (!$input->getArgument('type')) {
            throw new RuntimeException('Argument type is required');
        }

        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        // FILTER ADDONS =============================================

        if ($input->getOption('rest') == TRUE) {
            $addons = $addons->findBy(['this->github->extra' => NULL]);
        }

        switch ($input->getArgument('type')) {
            case 'composer':
                $addons = $addons->findBy(['type' => Addon::TYPE_COMPOSER]);
                break;
            case 'bower':
                $addons = $addons->findBy(['type' => Addon::TYPE_BOWER]);
                break;
            case 'unknown':
                $addons = $addons->findBy(['type' => Addon::TYPE_UNKNOWN]);
                break;
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            // Composer
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_COMPOSER])) {
                if (($response = $this->github->composer($addon->owner, $addon->name))) {
                    if ($addon->type !== Addon::TYPE_COMPOSER) {
                        $addon->type = Addon::TYPE_COMPOSER;
                    }

                    $addon->github->extra->append('github', ['composer' => $response]);

                    if (($url = $addon->github->extra->get(['github', 'composer', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('composer', $composer);
                        } else {
                            $output->writeln('Skip (composer) [invalid composer.json]: ' . $addon->fullname);
                        }
                    } else {
                        $output->writeln('Skip (composer) [can not download composer.json]: ' . $addon->fullname);
                    }
                } else {
                    $output->writeln('Skip (composer): ' . $addon->fullname);
                }
            }

            // Bower
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_BOWER])) {
                if (($response = $this->github->bower($addon->owner, $addon->name))) {
                    if ($addon->type !== Addon::TYPE_BOWER) {
                        $addon->type = Addon::TYPE_BOWER;
                    }

                    $addon->github->extra->append('github', ['bower' => $response]);

                    if (($url = $addon->github->extra->get(['github', 'bower', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('bower', $composer);
                        } else {
                            $output->writeln('Skip (bower) [invalid bower.json]: ' . $addon->fullname);
                        }
                    } else {
                        $output->writeln('Skip (bower) [can not download bower.json]: ' . $addon->fullname);
                    }
                } else {
                    $output->writeln('Skip (bower): ' . $addon->fullname);
                }
            }

            // Untype
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN])) {
                $addon->type = Addon::TYPE_UNTYPE;
            }

            // Persist
            $this->addonRepository->persistAndFlush($addon);

            // Increase counting
            $counter++;
        }

        $output->writeln(sprintf('Updated %s addons files', $counter));
    }

}
