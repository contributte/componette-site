<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Github;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeReleasesCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:github:sync:releases';

	private AddonFacade $addonFacade;

	private GithubReleaseRepository $githubReleaseRepository;

	private GithubService $github;

	private EntityManagerInterface $em;

	public function __construct(
		AddonFacade $addonFacade,
		GithubReleaseRepository $githubReleaseRepository,
		GithubService $github,
		EntityManagerInterface $em
	)
	{
		parent::__construct();
		$this->addonFacade = $addonFacade;
		$this->githubReleaseRepository = $githubReleaseRepository;
		$this->github = $github;
		$this->em = $em;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Synchronize addon releases');

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
		foreach ($addons as $addon) {
			// Skip non-github reference
			if (!$addon->getGithub()) {
				continue;
			}

			// Fetch all already saved github releases
			$storedReleases = [];
			foreach ($addon->getGithub()->getReleases() as $release) {
				$storedReleases[$release->getGid()] = $release;
			}

			// Get all releases
			$responses = $this->github->allReleases($addon->getAuthor(), $addon->getName(), GithubService::MEDIATYPE_HTML);
			if ($responses) {
				foreach ($responses as $response) {

					// Get response body as releases
					/** @var array<array<number|string>> $releases */
					$releases = $response->getJsonBody();
					if ($releases) {
						// Iterate over all releases
						foreach ($releases as $release) {
							$releaseId = $release['id'];

							// Try find release by ID
							if (isset($storedReleases[$releaseId])) {
								// Use already added
								$githubRelease = $storedReleases[$releaseId];
							} else {
								// Create new one
								$githubRelease = new GithubRelease(
									$addon->getGithub(),
									(int) $release['id'],
									(string) $release['name'],
									(string) $release['tag_name']
								);
								$addon->getGithub()->addRelease($githubRelease);
							}

							$githubRelease->setName((string) $release['name']);
							$githubRelease->setTag((string) $release['tag_name']);
							$githubRelease->setDraft((bool) $release['draft']);
							$githubRelease->setPrerelease((bool) $release['prerelease']);
							$githubRelease->setCreatedAt(new DateTimeImmutable((string) $release['created_at']));
							$githubRelease->setCrawledAt(new DateTimeImmutable());
							$githubRelease->setPublishedAt(new DateTimeImmutable((string) $release['published_at']));
							$githubRelease->setBody((string) $release['body_html']);

							// Unset from array
							unset($storedReleases[$releaseId]);
						}
					} else {
						$output->writeln('Skip (no releases): ' . $addon->getFullname());
					}
				}

				// Save new or updated releases
				$this->em->persist($addon->getGithub());
				$this->em->flush();

				// If there are some left releases, remove them
				if (count($storedReleases) > 0) {
					foreach ($storedReleases as $release) {
						$this->em->remove($release);
					}
					$this->em->flush();
				}
			} else {
				$output->writeln('Skip (no response): ' . $addon->getFullname());
			}

			$counter++;
		}

		$output->writeln(sprintf('Updated %s addons', $counter));

		return 0;
	}

}
