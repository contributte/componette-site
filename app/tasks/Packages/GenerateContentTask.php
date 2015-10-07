<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Metadata;
use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Tasks\BaseTask;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;

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
        /** @var ICollection|Package[] $packages */
        $packages = $this->packagesRepository->findActive();

        // FILTER PACKAGES ===========================================

        if (isset($args['rest']) && $args['rest'] === TRUE) {
            $packages = $packages->findBy(['this->metadata->content' => NULL]);
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($packages as $package) {
            // Skip packages with bad data
            if (($extra = $package->metadata->extra)) {
                if (($url = $extra->get(['github', 'readme', 'download_url'], NULL))) {
                    $content = @file_get_contents($url);

                    if ($content) {
                        // Increase counting
                        $counter++;

                        // Content
                        $package->metadata->content = $content;

                        // Readme type
                        if ($package->metadata->readme === NULL) {
                            $url = strtolower($url);
                            if (Strings::endsWith($url, 'md')) {
                                $package->metadata->readme = Metadata::README_MARKDOWN;
                            } else if (Strings::endsWith($url, 'texy')) {
                                $package->metadata->readme = Metadata::README_TEXY;
                            } else {
                                $package->metadata->readme = Metadata::README_RAW;
                            }
                        }
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
