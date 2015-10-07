<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\WebServices\Bower\Service;
use App\Tasks\BaseTask;
use Nette\Utils\Arrays;

final class UpdateBowerTask extends BaseTask
{

    /** @var PackagesRepository */
    private $packagesRepository;

    /** @var Service */
    private $bower;

    /**
     * @param PackagesRepository $packagesRepository
     * @param Service $bower
     */
    function __construct(
        PackagesRepository $packagesRepository,
        Service $bower
    )
    {
        $this->packagesRepository = $packagesRepository;
        $this->bower = $bower;
    }

    /**
     * @param array $args
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var Package[] $packages */
        $packages = $this->packagesRepository->findBowers();

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($packages as $package) {

            // Skip packages with bad data
            if (($extra = $package->metadata->extra)) {
                if (($bower = $extra->get('bower', FALSE))) {
                    // Increase counting
                    $counter++;

                    // Downloads
                    if (($stats = $this->bower->repo($bower['name']))) {
                        $package->metadata->downloads = Arrays::get($stats, ['hits'], 0);
                    }

                    // Keywords
                    $package->metadata->tags = Arrays::get($bower, 'keywords', []);

                    $this->packagesRepository->persistAndFlush($package);
                } else {
                    $this->log('Skip (bower) [no bower data]: ' . $package->repository);
                }
            } else {
                $this->log('Skip (bower) [no extra data]: ' . $package->repository);
            }
        }
        return $counter;
    }
}
