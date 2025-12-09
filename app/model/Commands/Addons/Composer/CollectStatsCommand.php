<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\WebServices\Composer\ComposerService;
use Doctrine\ORM\EntityManagerInterface;
use Nette\InvalidStateException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\Debugger;

final class CollectStatsCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:composer:collect';

	private AddonRepository $addonRepository;

	private ComposerService $composer;

	private EntityManagerInterface $em;

	public function __construct(AddonRepository $addonRepository, ComposerService $composer, EntityManagerInterface $em)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
		$this->composer = $composer;
		$this->em = $em;
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
		$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_COMPOSER]);

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			// Skip non-github reference
			if (!$addon->getGithub()) {
				continue;
			}

			try {
				// Skip addon without data
				$githubComposer = $addon->getGithub()->getMasterComposer();
				if ($githubComposer) {
					$composerName = $githubComposer->getComposerName();
					if (!$composerName) {
						throw new InvalidStateException('No composer name at ' . $addon->getFullname());
					}

					[$vendor, $repo] = explode('/', $composerName);

					$response = $this->composer->stats($vendor, $repo);
					if ($response->isOk()) {
						$stats = new ComposerStatistics($addon, ComposerStatistics::TYPE_ALL, ComposerStatistics::CUSTOM_ALL);
						$stats->setJson($response->getJsonBody());
						$addon->addComposerStatistics($stats);
					} else {
						$output->writeln('Skip (composer stats) [no stats data]: ' . $addon->getFullname());
					}

					// Persist
					$this->em->persist($addon);
					$this->em->flush();

					// Increase counting
					$counter++;
				} else {
					$output->writeln('Skip (composer stats) [no composer data]: ' . $addon->getFullname());
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
