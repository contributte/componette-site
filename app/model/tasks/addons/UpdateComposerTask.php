<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Composer\Composer;
use App\Model\WebServices\Composer\Service;
use Exception;
use Nette\InvalidStateException;
use Nette\Utils\Arrays;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;

final class UpdateComposerTask extends BaseAddonTask
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
     * @return int
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

                        // Create composer entity if not exist
                        if (!$addon->composer) {
                            $addon->composer = new Composer();
                        }

                        // Basic info
                        $addon->composer->name = Arrays::get($composer, 'name', NULL);
                        $addon->composer->description = Arrays::get($composer, 'description', NULL);
                        $addon->composer->type = Arrays::get($composer, 'type', NULL);

                        // Downloads
                        if (($stats = $this->composer->repo($owner, $repo))) {
                            $addon->composer->downloads = Arrays::get($stats, ['package', 'downloads', 'total'], 0);
                        }

                        // Keywords
                        $keywords = Arrays::get($composer, 'keywords', []);
                        $addon->composer->keywords = $keywords ? implode(',', $keywords) : NULL;

                        // Persist
                        $this->addonRepository->persistAndFlush($addon);

                        // Increase counting
                        $counter++;
                    } else {
                        $this->log('Skip (composer) [no composer data]: ' . $addon->fullname);
                    }
                } else {
                    $this->log('Skip (composer) [no extra data]: ' . $addon->fullname);
                }
            } catch (Exception $e) {
                Debugger::log($e, Debugger::EXCEPTION);
                $this->log('Skip (composer) [exception]: ' . $e->getMessage());
            }
        }

        return $counter;
    }
}
