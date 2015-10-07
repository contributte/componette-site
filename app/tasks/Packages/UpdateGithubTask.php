<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\WebServices\Github\Service;
use App\Tasks\BaseTask;
use Nextras\Orm\Collection\ICollection;

final class UpdateGithubTask extends BaseTask
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
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Package[] $packages */
        $packages = $this->packagesRepository->findActive();

        // FILTER PACKAGES ===========================================

        if (isset($args['rest']) && $args['rest'] === TRUE) {
            $packages = $packages->findBy(['this->metadata->extra' => NULL]);
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($packages as $package) {
            list ($owner, $repo) = explode('/', $package->repository);

            // Increase counting
            $counter++;

            // Readme
            if (($response = $this->github->readme($owner, $repo))) {
                $package->metadata->extra->append('github', ['readme' => $response]);
            } else {
                $this->log('Skip (readme): ' . $package->repository);
            }

            // Composer
            if (($response = $this->github->composer($owner, $repo))) {
                $package->metadata->extra->append('github', ['composer' => $response]);

                if (($url = $package->metadata->extra->get(['github', 'composer', 'download_url'], NULL))) {
                    if (($content = @file_get_contents($url))) {
                        $composer = @json_decode($content, TRUE);
                        $package->metadata->extra->set('composer', $composer);
                    } else {
                        $this->log('Skip (composer) [invalid composer.json]: ' . $package->repository);
                    }
                } else {
                    $this->log('Skip (composer) [cant download composer.json]: ' . $package->repository);
                }
            } else {
                $this->log('Skip (composer): ' . $package->repository);
            }

            $this->packagesRepository->persistAndFlush($package);
        }

        return $counter;
    }

}
