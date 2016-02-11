<?php

namespace App\Model\Tasks\Addons;

use App\Core\Cache\Cacher;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebServices\Github\Service;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;

final class UpdateGithubTask extends BaseAddonTask
{

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
        parent::__construct($addonRepository);
        $this->github = $github;
        $this->cacher = $cacher;
    }

    /**
     * @param array $args
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        if (isset($args['rest']) && $args['rest'] === TRUE) {
            $addons = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED]);
        } else if (isset($args['type'])) {
            switch ($args['type']) {
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
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        $added = 0;
        foreach ($addons as $addon) {

            // Base metadata
            $response = $this->github->repo($addon->owner, $addon->name);
            if ($response && !isset($response['message'])) {
                $github = $addon->github;
                // Increase adding counting
                if ($addon->state == Addon::STATE_QUEUED) {
                    $added++;
                }

                // Parse owner & repo name
                $matches = Strings::match($response['full_name'], '#' . Addon::GITHUB_REGEX . '#');
                if (!$matches) {
                    $this->log('Skip (invalid addon name): ' . $response['full_name']);
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
                $github->description = $response['description'];
                $github->homepage = !empty($response['homepage']) ? $response['homepage'] : NULL;
                $github->stars = $response['stargazers_count'];
                $github->watchers = $response['watchers_count'];
                $github->issues = $response['open_issues_count'];
                $github->fork = boolval($response['fork']);
                $github->language = $response['language'];
                $github->forks = $response['forks_count'];
                $github->createdAt = new DateTime($response['created_at']);
                $github->updatedAt = new DateTime($response['updated_at']);
                $github->pushedAt = new DateTime($response['pushed_at']);
                $addon->state = Addon::STATE_ACTIVE;
            } else {
                $addon->state = Addon::STATE_ARCHIVED;

                if (isset($response['message'])) {
                    $this->log('Skip (' . $response['message'] . '): ' . $addon->fullname);
                } else {
                    $this->log('Skip (base): ' . $addon->fullname);
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

        return $counter;
    }

}
