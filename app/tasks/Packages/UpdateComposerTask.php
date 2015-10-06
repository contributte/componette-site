<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\WebServices\Composer\Service;
use App\Tasks\BaseTask;
use Nette\Utils\Arrays;

final class UpdateComposerTask extends BaseTask
{

    /** @var PackagesRepository */
    private $packagesRepository;

    /** @var Service */
    private $composer;

    /**
     * @param PackagesRepository $packagesRepository
     * @param Service $composer
     */
    function __construct(
        PackagesRepository $packagesRepository,
        Service $composer
    )
    {
        $this->packagesRepository = $packagesRepository;
        $this->composer = $composer;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function run(array $args = [])
    {
        /** @var Package[] $packages */
        $packages = $this->packagesRepository->findActive();

        foreach ($packages as $package) {

            // Skip packages with bad data
            if (($extra = $package->metadata->extra)) {
                if (($composer = $extra->get('composer', FALSE))) {
                    $meta = $package->metadata;
                    list ($owner, $repo) = explode('/', $composer['name']);

                    // Downloads
                    if (($stats = $this->composer->repo($owner, $repo))) {
                        $meta->downloads = Arrays::get($stats, ['package', 'downloads', 'total'], 0);
                    }

                    // Keywords
                    $meta->tags = Arrays::get($composer, 'keywords', []);

                    $this->packagesRepository->persistAndFlush($package);
                } else {
                    $this->log('Skip (composer) [no composer data]: ' . $package->repository);
                }
            } else {
                $this->log('Skip (composer) [no extra data]: ' . $package->repository);
            }
        }
        return TRUE;
    }
}
