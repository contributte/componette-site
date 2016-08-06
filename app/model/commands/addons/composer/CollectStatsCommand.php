<?php

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebServices\Composer\Service;
use Exception;
use Nette\InvalidStateException;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tracy\Debugger;

final class CollectStatsCommand extends BaseCommand
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var Service */
    private $composer;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $composer
     */
    public function __construct(AddonRepository $addonRepository, Service $composer)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->composer = $composer;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:addons:composer:collect')
            ->setDescription('Update composer stats');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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
                            $output->writeln('Skip (composer stats) [no stats data]: ' . $addon->fullname);
                        }

                        // Persist
                        $this->addonRepository->persistAndFlush($addon);

                        // Increase counting
                        $counter++;
                    } else {
                        $output->writeln('Skip (composer stats) [no composer data]: ' . $addon->fullname);
                    }
                } else {
                    $output->writeln('Skip (composer stats) [no extra data]: ' . $addon->fullname);
                }
            } catch (Exception $e) {
                Debugger::log($e, Debugger::EXCEPTION);
                $output->writeln('Skip (composer stats) [exception]: ' . $e->getMessage());
            }
        }

        $output->writeln(sprintf('Updated %s packages', $counter));
    }
}
