<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Github;

use App\Model\Caching\CacheCleaner;
use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\Strings;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeCommand extends BaseCommand
{

	/** @var AddonFacade */
	private $addonFacade;

	/** @var GithubService */
	private $github;

	/** @var CacheCleaner */
	private $cacher;

	public function __construct(AddonFacade $addonFacade, GithubService $github, CacheCleaner $cacher)
	{
		parent::__construct();
		$this->addonFacade = $addonFacade;
		$this->github = $github;
		$this->cacher = $cacher;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName('addons:github:sync')
			->setDescription('Synchronize github detailed information');

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

	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		$addons = $this->addonFacade->find($input);

		// DO YOUR JOB ===============================================

		$counter = 0;
		$added = 0;

		/** @var Addon $addon */
		foreach ($addons as $addon) {

			// Base metadata
			$response = $this->github->repo($addon->author, $addon->name);
			$body = $response->getJsonBody();

			if (!$response->isOk()) {
				// Create github entity if not exist
				if (!$addon->github) {
					$addon->github = new Github();
				}
				$addon->state = Addon::STATE_ARCHIVED;

				$output->writeln('Skip (archived): ' . $addon->fullname);
			} elseif (!$body || isset($body['message'])) {
				if (isset($response['message'])) {
					$output->writeln('Skip (' . $response['message'] . '): ' . $addon->fullname);
				} else {
					$output->writeln('Skip (base): ' . $addon->fullname);
				}
			} else {
				// Create github entity if not exist
				if (!$addon->github) {
					$addon->github = new Github();
				}

				// Increase adding counting
				if ($addon->state === Addon::STATE_QUEUED) {
					$added++;
				}

				// Parse author & repo name
				$matches = Strings::match($body['full_name'], '#' . Addon::GITHUB_REGEX . '#');
				if (!$matches) {
					$output->writeln('Skip (invalid addon name): ' . $body['full_name']);
					continue;
				}

				[$all, $author, $name] = $matches;

				// Update author & repo name if it is not same
				if ($addon->author !== $author) {
					$addon->author = $author;
				}

				if ($addon->name !== $name) {
					$addon->name = $name;
				}

				// Update basic information
				$addon->github->description = $body['description'];
				$addon->github->homepage = !empty($body['homepage']) ? $body['homepage'] : null;
				$addon->github->stars = $body['stargazers_count'];
				$addon->github->watchers = $body['watchers_count'];
				$addon->github->issues = $body['open_issues_count'];
				$addon->github->fork = boolval($body['fork']);
				$addon->github->language = $body['language'];
				$addon->github->forks = $body['forks_count'];
				$addon->github->createdAt = new DateTimeImmutable($body['created_at']);
				$addon->github->updatedAt = new DateTimeImmutable($body['updated_at']);
				$addon->github->pushedAt = new DateTimeImmutable($body['pushed_at']);
				$addon->state = Addon::STATE_ACTIVE;
				// Calculate rating
				$addon->rating = $addon->github->stars * 2 + $addon->github->watchers * 3 + $addon->github->forks;
			}

			$addon->updatedAt = new DateTimeImmutable();
			$this->addonFacade->persist($addon);
			$this->addonFacade->flush();

			// Increase counting
			$counter++;
		}

		if ($added > 0) {
			$this->cacher->cleanByTags(['routing']);
		}

		$output->writeln(sprintf('Updated %s addons', $counter));
	}

}
