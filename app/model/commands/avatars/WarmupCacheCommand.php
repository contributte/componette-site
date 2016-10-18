<?php

namespace App\Model\Commands\Avatars;

use App\Model\Commands\BaseCommand;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebImages\GithubImages;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class WarmupCacheCommand extends BaseCommand
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var GithubService */
    private $github;

    /** @var GithubImages */
    private $githubImages;

    /**
     * @param AddonRepository $addonRepository
     * @param GithubService $github
     * @param GithubImages $githubImages
     */
    public function __construct(AddonRepository $addonRepository, GithubService $github, GithubImages $githubImages)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->github = $github;
        $this->githubImages = $githubImages;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('avatars:warmup')
            ->setDescription('Synchronize avatars cache');
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

        $counter = 0;
        foreach ($addons as $addon) {

            // User avatar
            $response = $this->github->avatar($addon->owner, FALSE);

            if ($response->isOk() && $response->hasInfo('filetime')) {
                // If avatar was update before less then a week, remove it from filesystem
                if (DateTime::from($response->getInfo('filetime')) > DateTime::from('- 1 week')) {
                    $this->githubImages->remove(['type' => 'avatar', 'owner' => $addon->owner]);

                    // Increase counting
                    $counter++;
                }
            }
        }

        $output->writeln(sprintf('Updated %s avatars', $counter));
    }

}
