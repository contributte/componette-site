<?php

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\WebServices\Composer\ComposerService;
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

	/** @var ComposerService */
	private $composer;

	/**
	 * @param AddonRepository $addonRepository
	 * @param ComposerService $composer
	 */
	public function __construct(AddonRepository $addonRepository, ComposerService $composer)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
		$this->composer = $composer;
	}

	/**
	 * Configure command
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this
			->setName('addons:composer:collect')
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
		$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_COMPOSER]);

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			try {
				// Skip addon without data
				$composer = $addon->github->masterComposer;
				if ($composer) {

					if (!$composer->name) {
						throw new InvalidStateException('No composer name at ' . $addon->fullname);
					}

					list ($vendor, $repo) = explode('/', $composer->name);

					$response = $this->composer->stats($vendor, $repo);
					if ($response->isOk()) {
						$stats = new ComposerStatistics();
						$stats->addon = $addon;
						$stats->type = ComposerStatistics::TYPE_ALL;
						$stats->custom = ComposerStatistics::CUSTOM_ALL;
						$stats->json = $response->getJsonBody();
						$addon->composerStatistics->add($stats);
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
			} catch (Exception $e) {
				Debugger::log($e, Debugger::EXCEPTION);
				$output->writeln('Skip (composer stats) [exception]: ' . $e->getMessage());
			}
		}

		$output->writeln(sprintf('Updated %s packages', $counter));
	}

}
