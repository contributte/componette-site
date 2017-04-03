<?php

namespace App\Model\Commands\Addons\Github;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubComposer\GithubComposer;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeFilesCommand extends BaseCommand
{

	/** @var AddonFacade */
	private $addonFacade;

	/** @var GithubService */
	private $github;

	/**
	 * @param AddonFacade $addonFacade
	 * @param GithubService $github
	 */
	public function __construct(AddonFacade $addonFacade, GithubService $github)
	{
		parent::__construct();
		$this->addonFacade = $addonFacade;
		$this->github = $github;
	}

	/**
	 * Configure command
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this
			->setName('addons:github:sync:files')
			->setDescription('Synchronize github files (composer.json, bower.json)');

		$this->addArgument(
			'type',
			InputOption::VALUE_REQUIRED,
			'What type should be synchronized',
			'all'
		);

		$this->addOption(
			'rest',
			NULL,
			InputOption::VALUE_NONE,
			'Should synchronize only queued addons?'
		);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// @todo maybe catch exceptions and update output??
		$addons = $this->addonFacade->find($input);

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			// Composer
			if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_COMPOSER])) {
				$response = $this->github->composer($addon->author, $addon->name);

				if ($response->isOk()) {
					if ($addon->type !== Addon::TYPE_COMPOSER) {
						$addon->type = Addon::TYPE_COMPOSER;
					}

					$body = $response->getJsonBody();

					/** @var GithubComposer $composer */
					$composer = $addon->github->masterComposer;

					if (!$composer) {
						$composer = new GithubComposer();
						$composer->custom = GithubComposer::BRANCH_MASTER;
						$composer->type = GithubComposer::TYPE_BRANCH;
						$composer->json = $body;
						$addon->github->composers->add($composer);
					} else {
						$composer->json = $body;
						$composer->updatedAt = new DateTime();
					}

					$this->addonFacade->persist($composer);
				} else {
					$output->writeln('Skip (composer): ' . $addon->fullname);
				}
			}

			// Untype
			if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN])) {
				$addon->type = Addon::TYPE_UNTYPE;
			}

			// Persist
			$this->addonFacade->persist($addon);
			$this->addonFacade->flush();

			// Increase counting
			$counter++;
		}

		$output->writeln(sprintf('Updated %s addons files', $counter));
	}

}
