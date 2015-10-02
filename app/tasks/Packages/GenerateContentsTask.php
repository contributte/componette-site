<?php

namespace App\Tasks\Packages;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\Packages\Content\ContentManager;
use App\Tasks\BaseTask;
use Nette\Utils\Arrays;

final class GenerateContentsTask extends BaseTask
{

    /** @var PackagesRepository */
    private $packagesRepository;

    /** @var ContentManager */
    private $contentManager;

    /**
     * @param PackagesRepository $packagesRepository
     * @param ContentManager $contentManager
     */
    function __construct(
        PackagesRepository $packagesRepository,
        ContentManager $contentManager
    )
    {
        $this->packagesRepository = $packagesRepository;
        $this->contentManager = $contentManager;
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
            if (($data = $package->metadata->extra)) {
                $data = json_decode($data, TRUE);

                if (($url = Arrays::get($data, ['github', 'readme', 'download_url'], NULL))) {
                    $content = @file_get_contents($url);

                    if ($content) {
                        $package->metadata->content = $content;
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
