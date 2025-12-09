<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Github;

use App\Model\Caching\CacheCleaner;
use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use DateTimeImmutable;
use Nette\Utils\Strings;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:github:sync';

	private AddonFacade $addonFacade;

	private GithubService $github;

	private CacheCleaner $cacher;

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
			->setName(self::$defaultName)
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

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$addons = $this->addonFacade->find($input);

		// DO YOUR JOB ===============================================

		$counter = 0;
		$added = 0;

		/** @var Addon $addon */
		foreach ($addons as $addon) {

			// Base metadata
			$response = $this->github->repo($addon->getAuthor(), $addon->getName());
			$body = $response->getJsonBody();

			if (!$response->isOk()) {
				// Create github entity if not exist
				if (!$addon->getGithub()) {
					$github = new Github($addon);
					$addon->setGithub($github);
				}

				$addon->setState(Addon::STATE_ARCHIVED);

				$output->writeln('Skip (archived): ' . $addon->getFullname());
			} elseif (!$body || isset($body['message'])) {
				if (isset($body['message'])) {
					$output->writeln('Skip (' . $body['message'] . '): ' . $addon->getFullname());
				} else {
					$output->writeln('Skip (base): ' . $addon->getFullname());
				}
			} else {
				// Create github entity if not exist
				if (!$addon->getGithub()) {
					$github = new Github($addon);
					$addon->setGithub($github);
				}

				// Increase adding counting
				if ($addon->getState() === Addon::STATE_QUEUED) {
					$added++;
				}

				// Parse author & repo name
				$matches = Strings::match($body['full_name'], '#' . Addon::GITHUB_REGEX . '#');
				if (!$matches) {
					$output->writeln('Skip (invalid addon name): ' . $body['full_name']);
					continue;
				}

				[, $author, $name] = $matches;

				// Update author & repo name if it is not same
				if ($addon->getAuthor() !== $author) {
					$addon->setAuthor($author);
				}

				if ($addon->getName() !== $name) {
					$addon->setName($name);
				}

				// Update basic information
				$addon->getGithub()->setDescription($body['description']);
				$addon->getGithub()->setHomepage(!empty($body['homepage']) ? $body['homepage'] : null);
				$addon->getGithub()->setStars($body['stargazers_count']);
				$addon->getGithub()->setWatchers($body['watchers_count']);
				$addon->getGithub()->setIssues($body['open_issues_count']);
				$addon->getGithub()->setFork((bool) $body['fork']);
				$addon->getGithub()->setLanguage($body['language']);
				$addon->getGithub()->setForks($body['forks_count']);
				$addon->getGithub()->setCreatedAt(new DateTimeImmutable($body['created_at']));
				$addon->getGithub()->setUpdatedAt(new DateTimeImmutable($body['updated_at']));
				$addon->getGithub()->setPushedAt(new DateTimeImmutable($body['pushed_at']));
				$addon->setState(Addon::STATE_ACTIVE);
				// Calculate rating
				$addon->setRating($addon->getGithub()->getStars() * 2 + $addon->getGithub()->getWatchers() * 3 + $addon->getGithub()->getForks());
			}

			$addon->setUpdatedAt(new DateTimeImmutable());
			$this->addonFacade->persist($addon);
			$this->addonFacade->flush();

			// Increase counting
			$counter++;
		}

		if ($added > 0) {
			$this->cacher->cleanByTags(['routing']);
		}

		$output->writeln(sprintf('Updated %s addons', $counter));

		return 0;
	}

}
