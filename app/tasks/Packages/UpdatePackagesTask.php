<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\WebServices\Github\Service;
use App\Tasks\BaseTask;
use Nette\Utils\DateTime;

final class UpdatePackagesTask extends BaseTask
{

    /** @var PackagesRepository */
    private $packagesRepository;

    /** @var Service */
    private $github;

    /**
     * @param PackagesRepository $packagesRepository
     * @param Service $github
     */
    function __construct(
        PackagesRepository $packagesRepository,
        Service $github
    )
    {
        $this->packagesRepository = $packagesRepository;
        $this->github = $github;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function run(array $args = [])
    {
        /** @var Package[] $packages */

        if (isset($args['rest'])) {
            $packages = $this->packagesRepository->findBy(['this->metadata->owner' => NULL]);
        } else {
            $packages = $this->packagesRepository->findAll();
        }

        foreach ($packages as $package) {
            list ($owner, $repo) = explode('/', $package->repository);
            $meta = $package->metadata;

            // Process only success responses form Github

            // Base metadata
            if (($response = $this->github->repo($owner, $repo))) {
                $meta->owner = $response['owner']['login'];
                $meta->name = $response['full_name'];
                $meta->description = $response['description'];
                $meta->readme = 'MARKDOWN';
                $meta->homepage = !empty($response['homepage']) ? $response['homepage'] : NULL;
                $meta->stars = $response['stargazers_count'];
                $meta->watchers = $response['watchers_count'];
                $meta->issues = $response['open_issues_count'];
                $meta->forks = $response['forks_count'];
                $meta->pushed = new DateTime($response['pushed_at']);
            } else {
                $this->log('Skip (base): ' . $package->repository);
            }

            // Readme
            if (($response = $this->github->readme($owner, $repo))) {
                $meta->extra['github']['readme'] = $response;
            } else {
                $this->log('Skip (readme): ' . $package->repository);
            }

            $this->packagesRepository->persistAndFlush($package);
        }

        return TRUE;
    }
}
