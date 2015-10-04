<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Tasks\BaseTask;

final class GenerateContentTask extends BaseTask
{

    /** @var PackagesRepository */
    private $packagesRepository;

    /**
     * @param PackagesRepository $packagesRepository
     */
    function __construct(
        PackagesRepository $packagesRepository
    )
    {
        $this->packagesRepository = $packagesRepository;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function run(array $args = [])
    {
        /** @var Package[] $packages */
        $packages = $this->packagesRepository->findAll();
        foreach ($packages as $package) {

            // Skip packages with bad data
            if (($extra = $package->metadata->extra)) {
                if (($url = $extra->get(['github', 'readme', 'download_url'], NULL))) {
                    $content = @file_get_contents($url);

                    if ($content) {
                        $package->metadata->content = $content;
                        $this->packagesRepository->persistAndFlush($package);
                    } else {
                        $this->log('Skip (content) [failed download content]: ' . $package->repository);
                    }
                } else {
                    $this->log('Skip (content) [no github readme data]: ' . $package->repository);
                }
            } else {
                $this->log('Skip (content) [no extra data]: ' . $package->repository);
            }
        }


        return TRUE;
    }
}
