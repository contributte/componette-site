<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Github;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubComposer\GithubComposer;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use DateTimeImmutable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeFilesCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:github:sync:files';

	private AddonFacade $addonFacade;

	private GithubService $github;

	public function __construct(AddonFacade $addonFacade, GithubService $github)
	{
		parent::__construct();
		$this->addonFacade = $addonFacade;
		$this->github = $github;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Synchronize github files (composer.json, bower.json)');

		$this->addArgument(
			'type',
			InputOption::VALUE_REQUIRED,
			'What type should be synchronized',
			'all'
		);

		$this->addOption(
			'rest',
			null,
			InputOption::VALUE_NONE,
			'Should synchronize only queued addons?'
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		// @todo maybe catch exceptions and update output??
		$addons = $this->addonFacade->find($input);

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			// Composer
			if (in_array($addon->getType(), [Addon::TYPE_UNKNOWN, Addon::TYPE_COMPOSER])) {
				// Skip non-github reference
				if (!$addon->getGithub()) {
					continue;
				}

				$response = $this->github->composer($addon->getAuthor(), $addon->getName());

				if ($response->isOk()) {
					if ($addon->getType() !== Addon::TYPE_COMPOSER) {
						$addon->setType(Addon::TYPE_COMPOSER);
					}

					$body = $response->getJsonBody();
					$composer = $addon->getGithub()->getMasterComposer();

					if (!$composer) {
						$composer = new GithubComposer($addon->getGithub(), GithubComposer::TYPE_BRANCH, GithubComposer::BRANCH_MASTER);
						$composer->setJson($body);
						$addon->getGithub()->addComposer($composer);
					} else {
						$composer->setJson($body);
						$composer->setUpdatedAt(new DateTimeImmutable());
					}

					$this->addonFacade->persist($composer);
				} else {
					$output->writeln('Skip (composer): ' . $addon->getFullname());
				}
			}

			// Untype
			if ($addon->getType() === Addon::TYPE_UNKNOWN) {
				$addon->setType(Addon::TYPE_UNTYPE);
			}

			// Persist
			$this->addonFacade->persist($addon);
			$this->addonFacade->flush();

			// Increase counting
			$counter++;
		}

		$output->writeln(sprintf('Updated %s addons files', $counter));

		return 0;
	}

}
