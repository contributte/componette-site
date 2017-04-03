<?php

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\WebServices\Composer\ComposerService;
use Exception;
use Nette\InvalidStateException;
use Nette\Utils\Arrays;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tracy\Debugger;

final class SynchronizeCommand extends BaseCommand
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
			->setName('addons:composer:sync')
			->setDescription('Synchronize composer detailed information');
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
					if (!isset($composer->name)) {
						throw new InvalidStateException('No composer name at ' . $addon->fullname);
					}

					list ($author, $repo) = explode('/', $composer->name);

					// Create composer entity if not exist
					if (!$addon->composer) {
						$addon->composer = new Composer();
					}

					// Basic info
					$addon->composer->name = $composer->get('name', NULL);
					$addon->composer->description = $composer->get('description', NULL);
					$addon->composer->type = $composer->get('type', NULL);

					// Keywords
					$keywords = (array) $composer->get('keywords', []);
					$addon->composer->keywords = $keywords ? implode(',', $keywords) : NULL;

					// Downloads
					$response = $this->composer->repo($author, $repo);
					if ($response->isOk()) {
						$addon->composer->downloads = Arrays::get($response->getJsonBody(), ['package', 'downloads', 'total'], 0);
					}

					// Persist
					$this->addonRepository->persistAndFlush($addon);

					// Increase counting
					$counter++;
				} else {
					$output->writeln('Skip (composer) [no composer data]: ' . $addon->fullname);
				}
			} catch (Exception $e) {
				Debugger::log($e, Debugger::EXCEPTION);
				$output->writeln('Skip (composer) [exception]: ' . $e->getMessage());
			}
		}

		$output->writeln(sprintf('Updated %s composer addons', $counter));
	}

}
