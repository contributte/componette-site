<?php

namespace App\Model\Commands\Addons\Bower;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Bower\Bower;
use App\Model\WebServices\Bower\BowerService;
use Nette\Utils\Arrays;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeCommand extends BaseCommand
{

	/** @var AddonRepository */
	private $addonRepository;

	/** @var BowerService */
	private $bower;

	/**
	 * @param AddonRepository $addonRepository
	 * @param BowerService $bower
	 */
	public function __construct(AddonRepository $addonRepository, BowerService $bower)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
		$this->bower = $bower;
	}

	/**
	 * Configure command
	 */
	protected function configure()
	{
		$this
			->setName('addons:bower:sync')
			->setDescription('Synchronize bower detailed information');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
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
					$response = $this->bower->repo($bower['name']);
					if ($response->isOk()) {
						$addon->bower->downloads = Arrays::get($response->getJsonBody(), ['hits'], 0);
					}

					// Name
					$addon->bower->name = Arrays::get($bower, 'name', NULL);

					// Keywords
					$keywords = Arrays::get($bower, 'keywords', []);
					$addon->bower->keywords = $keywords ? implode(',', $keywords) : NULL;

					// Persist
					$this->addonRepository->persistAndFlush($addon);

					// Increase counting
					$counter++;
				} else {
					$output->writeln('Skip (bower) [no bower data]: ' . $addon->fullname);
				}
			} else {
				$output->writeln('Skip (bower) [no extra data]: ' . $addon->fullname);
			}
		}

		$output->writeln(sprintf('Updated %s bower addons', $counter));
	}
}
