<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\WebServices\Composer\ComposerService;
use Nette\InvalidStateException;
use Nette\Utils\Arrays;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\Debugger;

final class SynchronizeCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:composer:sync';

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
			->setDescription('Synchronize composer detailed information');
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
					if (!isset($composer->name)) {
						throw new InvalidStateException('No composer name at ' . $addon->fullname);
					}

					 [$author, $repo] = explode('/', $composer->name);

					// Create composer entity if not exist
					if (!$addon->composer) {
						$addon->composer = new Composer();
					}

					// Basic info
					$addon->composer->name = $composer->get('name', null);
					$addon->composer->description = $composer->get('description', null);
					$addon->composer->type = $composer->get('type', null);

					// Keywords
					$keywords = (array) $composer->get('keywords', []);
					$addon->composer->keywords = $keywords ? implode(',', $keywords) : null;

					// Downloads
					$response = $this->composer->repo($author, $repo);
					if ($response->isOk()) {
						$addon->composer->downloads = Arrays::get($response->getJsonBody(), ['package', 'downloads', 'total'], 0);
					}

					// Persist
					$addon->composer->crawledAt = new DateTimeImmutable();
					$this->addonRepository->persistAndFlush($addon);

					// Increase counting
					$counter++;
				} else {
					$output->writeln('Skip (composer) [no composer data]: ' . $addon->fullname);
				}
			} catch (Throwable $e) {
				Debugger::log($e, Debugger::EXCEPTION);
				$output->writeln('Skip (composer) [exception]: ' . $e->getMessage());
			}
		}

		$output->writeln(sprintf('Updated %s composer addons', $counter));

		return 0;
	}

}
