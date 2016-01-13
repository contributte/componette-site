<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Bower\Bower;
use App\Model\WebServices\Bower\Service;
use Nette\Utils\Arrays;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;

final class UpdateBowerTask extends BaseAddonTask
{

    /** @var Service */
    private $bower;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $bower
     */
    public function __construct(AddonRepository $addonRepository, Service $bower)
    {
        parent::__construct($addonRepository);
        $this->bower = $bower;
    }

    /**
     * @param array $args
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findBowers();

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {

            // Skip addon with bad data
            if (($extra = $addon->github->extra)) {
                if (($bower = $extra->get('bower', FALSE))) {
                    // Create bower entity if not exist
                    if (!$addon->bower) {
                        $addon->bower = new Bower();
                    }

                    // Downloads
                    if (($stats = $this->bower->repo($bower['name']))) {
                        $addon->bower->downloads = Arrays::get($stats, ['hits'], 0);
                    }

                    // Keywords
                    $keywords = Arrays::get($bower, 'keywords', []);
                    $addon->bower->keywords = $keywords ? implode(',', $keywords) : NULL;

                    // Persist
                    $addon->bower->crawledAt = new DateTime();
                    $this->addonRepository->persistAndFlush($addon);

                    // Increase counting
                    $counter++;
                } else {
                    $this->log('Skip (bower) [no bower data]: ' . $addon->fullname);
                }
            } else {
                $this->log('Skip (bower) [no extra data]: ' . $addon->fullname);
            }
        }
        return $counter;
    }
}
