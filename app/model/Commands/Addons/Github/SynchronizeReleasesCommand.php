<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Github;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Github\GithubRepository;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeReleasesCommand extends BaseCommand
{

	/** @var AddonFacade */
	private $addonFacade;

	/** @var GithubRepository */
	private $githubRepository;

	/** @var GithubReleaseRepository */
	private $githubReleaseRepository;

	/** @var GithubService */
	private $github;

	public function __construct(
		AddonFacade $addonFacade,
		GithubRepository $githubRepository,
		GithubReleaseRepository $githubReleaseRepository,
		GithubService $github
	)
	{
		parent::__construct();
		$this->addonFacade = $addonFacade;
		$this->githubRepository = $githubRepository;
		$this->githubReleaseRepository = $githubReleaseRepository;
		$this->github = $github;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName('addons:github:sync:releases')
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
			if (!$addon->github) {
				continue;
			}

			// Fetch all already saved github releases
			$storedReleases = $addon->github->releases->get()->fetchPairs('gid');

			// Get all releases
			$responses = $this->github->allReleases($addon->author, $addon->name, GithubService::MEDIATYPE_HTML);
			if ($responses) {
				foreach ($responses as $response) {

					// Get response body as releases
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
								$githubRelease = new GithubRelease();
								$githubRelease->gid = $release['id'];
							}

							$githubRelease->name = $release['name'];
							$githubRelease->tag = $release['tag_name'];
							$githubRelease->draft = (bool) $release['draft'];
							$githubRelease->prerelease = (bool) $release['prerelease'];
							$githubRelease->createdAt = new DateTime($release['created_at']);
							$githubRelease->crawledAt = new DateTime();
							$githubRelease->publishedAt = new DateTime($release['published_at']);
							$githubRelease->body = $release['body_html'];

							// If its new one
							if (!$githubRelease->isPersisted()) {
								$addon->github->releases->add($githubRelease);
							}

							// Unset from array
							unset($storedReleases[$releaseId]);
						}
					} else {
						$output->writeln('Skip (no releases): ' . $addon->fullname);
					}
				}

				// Save new or updated releases
				$this->githubRepository->persistAndFlush($addon->github);

				// If there are some left releases, remove them
				if (count($storedReleases) > 0) {
					foreach ($storedReleases as $release) {
						$this->githubReleaseRepository->remove($release);
					}
				}
			} else {
				$output->writeln('Skip (no response): ' . $addon->fullname);
			}

			$counter++;
		}

		$output->writeln(sprintf('Updated %s addons', $counter));

		return 0;
	}

}
