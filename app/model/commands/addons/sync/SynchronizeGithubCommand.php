<?php

namespace App\Model\Commands\Addons\Sync;

use App\Core\Cache\Cacher;
use App\Model\Commands\BaseCommand;
use App\Model\Exceptions\RuntimeException;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Github\Github;
use App\Model\WebServices\Github\Service;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeGithubCommand extends BaseCommand
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var Service */
    private $github;

    /** @var Cacher */
    private $cacher;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $github
     * @param Cacher $cacher
     */
    public function __construct(AddonRepository $addonRepository, Service $github, Cacher $cacher)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->github = $github;
        $this->cacher = $cacher;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:addons:sync:github')
            ->setDescription('Synchronize github detailed information');

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

        if ($input->hasOption('rest')) {
            $addons = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED]);
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
        $added = 0;
        foreach ($addons as $addon) {

            // Base metadata
            $response = $this->github->repo($addon->owner, $addon->name);
            if ($response && !isset($response['message'])) {

                // Create github entity if not exist
                if (!$addon->github) {
                    $addon->github = new Github();
                }

                // Increase adding counting
                if ($addon->state == Addon::STATE_QUEUED) {
                    $added++;
                }

                // Parse owner & repo name
                $matches = Strings::match($response['full_name'], '#' . Addon::GITHUB_REGEX . '#');
                if (!$matches) {
                    $output->writeln('Skip (invalid addon name): ' . $response['full_name']);
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
                $addon->github->description = $response['description'];
                $addon->github->homepage = !empty($response['homepage']) ? $response['homepage'] : NULL;
                $addon->github->stars = $response['stargazers_count'];
                $addon->github->watchers = $response['watchers_count'];
                $addon->github->issues = $response['open_issues_count'];
                $addon->github->fork = boolval($response['fork']);
                $addon->github->language = $response['language'];
                $addon->github->forks = $response['forks_count'];
                $addon->github->createdAt = new DateTime($response['created_at']);
                $addon->github->updatedAt = new DateTime($response['updated_at']);
                $addon->github->pushedAt = new DateTime($response['pushed_at']);
                $addon->state = Addon::STATE_ACTIVE;
            } else {
                $addon->state = Addon::STATE_ARCHIVED;

                if (isset($response['message'])) {
                    $output->writeln('Skip (' . $response['message'] . '): ' . $addon->fullname);
                } else {
                    $output->writeln('Skip (base): ' . $addon->fullname);
                }
            }

            $addon->updatedAt = new DateTime();
            $this->addonRepository->persistAndFlush($addon);

            // Increase counting
            $counter++;
        }

        if ($added > 0) {
            $this->cacher->cleanByTags(['routing']);
        }

        $output->writeln(sprintf('Updated %s addons', $counter));
    }

}
