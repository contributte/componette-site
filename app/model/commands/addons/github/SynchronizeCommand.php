<?php

namespace App\Model\Commands\Addons\Github;

use App\Core\Cache\Cacher;
use App\Model\Commands\BaseCommand;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Github\Github;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeCommand extends BaseCommand
{

    /** @var AddonFacade */
    private $addonFacade;

    /** @var GithubService */
    private $github;

    /** @var Cacher */
    private $cacher;

    /**
     * @param AddonFacade $addonFacade
     * @param GithubService $github
     * @param Cacher $cacher
     */
    public function __construct(AddonFacade $addonFacade, GithubService $github, Cacher $cacher)
    {
        parent::__construct();
        $this->addonFacade = $addonFacade;
        $this->github = $github;
        $this->cacher = $cacher;
    }


    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('addons:github:sync')
            ->setDescription('Synchronize github detailed information');

        $this->addArgument(
            'type',
            InputOption::VALUE_REQUIRED,
            'What type should be synchronized',
            'all'
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
        $addons = $this->addonFacade->find($input);

        // DO YOUR JOB ===============================================

        $counter = 0;
        $added = 0;
        foreach ($addons as $addon) {

            // Base metadata
            $response = $this->github->repo($addon->owner, $addon->name);
            $body = $response->getJsonBody();

            if ($body && !isset($body['message'])) {

                // Create github entity if not exist
                if (!$addon->github) {
                    $addon->github = new Github();
                }

                // Increase adding counting
                if ($addon->state == Addon::STATE_QUEUED) {
                    $added++;
                }

                // Parse owner & repo name
                $matches = Strings::match($body['full_name'], '#' . Addon::GITHUB_REGEX . '#');
                if (!$matches) {
                    $output->writeln('Skip (invalid addon name): ' . $body['full_name']);
                    continue;
                }

                list ($all, $owner, $name) = $matches;

                // Update owner & repo name if it is not same
                if ($addon->owner !== $owner) {
                    $addon->owner = $owner;
                }

                if ($addon->name !== $name) {
                    $addon->name = $name;
                }

                // Update basic information
                $addon->github->description = $body['description'];
                $addon->github->homepage = !empty($body['homepage']) ? $body['homepage'] : NULL;
                $addon->github->stars = $body['stargazers_count'];
                $addon->github->watchers = $body['watchers_count'];
                $addon->github->issues = $body['open_issues_count'];
                $addon->github->fork = boolval($body['fork']);
                $addon->github->language = $body['language'];
                $addon->github->forks = $body['forks_count'];
                $addon->github->createdAt = new DateTime($body['created_at']);
                $addon->github->updatedAt = new DateTime($body['updated_at']);
                $addon->github->pushedAt = new DateTime($body['pushed_at']);
                $addon->state = Addon::STATE_ACTIVE;
            } else {
                if (isset($response['message'])) {
                    $output->writeln('Skip (' . $response['message'] . '): ' . $addon->fullname);
                } else {
                    $output->writeln('Skip (base): ' . $addon->fullname);
                }
            }

            $addon->updatedAt = new DateTime();
            $this->addonFacade->save($addon);

            // Increase counting
            $counter++;
        }

        if ($added > 0) {
            $this->cacher->cleanByTags(['routing']);
        }

        $output->writeln(sprintf('Updated %s addons', $counter));
    }

}
