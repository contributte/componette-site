<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\WebServices\Composer\ComposerService;
use Nette\InvalidStateException;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\Debugger;

final class CollectStatsCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:composer:collect';

	/** @var AddonRepository */
	private $addonRepository;

	/** @var ComposerService */
	private $composer;

	public function __construct(AddonRepository $addonRepository, ComposerService $composer)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
		$this->composer = $composer;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Update composer stats');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		/** @var ICollection|Addon[] $addons */
		$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_COMPOSER]);

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			// Skip non-github reference
			if (!$addon->github) {
				continue;
			}

			try {
				// Skip addon without data
				$composer = $addon->github->masterComposer;
				if ($composer) {

					if (!$composer->name) {
						throw new InvalidStateException('No composer name at ' . $addon->fullname);
					}

					[$vendor, $repo] = explode('/', $composer->name);

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
			} catch (Throwable $e) {
				Debugger::log($e, Debugger::EXCEPTION);
				$output->writeln('Skip (composer stats) [exception]: ' . $e->getMessage());
			}
		}

		$output->writeln(sprintf('Updated %s packages', $counter));

		return 0;
	}

}
