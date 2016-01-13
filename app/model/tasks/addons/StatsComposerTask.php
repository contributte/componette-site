<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebServices\Composer\Service;
use Exception;
use Nette\InvalidStateException;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;

final class StatsComposerTask extends BaseAddonTask
{

    /** @var Service */
    private $composer;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $composer
     */
    public function __construct(AddonRepository $addonRepository, Service $composer)
    {
        parent::__construct($addonRepository);
        $this->composer = $composer;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findComposers();

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            try {
                // Skip addon with bad data
                if (($extra = $addon->github->extra)) {
                    if (($composer = $extra->get('composer', FALSE))) {

                        if (!isset($composer['name'])) {
                            throw new InvalidStateException('No composer name at ' . $addon->fullname);
                        }
                        list ($owner, $repo) = explode('/', $composer['name']);

                        if (($stats = $this->composer->stats($owner, $repo))) {
                            $extra->set('composer-stats', ['all' => $stats]);
                        } else {
                            $this->log('Skip (composer stats) [no stats data]: ' . $addon->fullname);
                        }

                        // Persist
                        $addon->github->crawledAt = new DateTime();
                        $this->addonRepository->persistAndFlush($addon);

                        // Increase counting
                        $counter++;
                    } else {
                        $this->log('Skip (composer stats) [no composer data]: ' . $addon->fullname);
                    }
                } else {
                    $this->log('Skip (composer stats) [no extra data]: ' . $addon->fullname);
                }
            } catch (Exception $e) {
                Debugger::log($e, Debugger::EXCEPTION);
                $this->log('Skip (composer stats) [exception]: ' . $e->getMessage());
            }
        }

        return $counter;
    }
}
