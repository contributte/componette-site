<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Composer;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\WebServices\Composer\ComposerService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Nette\InvalidStateException;
use Nette\Utils\Arrays;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\Debugger;

final class SynchronizeCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:composer:sync';

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
			->setDescription('Synchronize composer detailed information');
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

					[$author, $repo] = explode('/', $composerName);

					// Create composer entity if not exist
					if (!$addon->getComposer()) {
						$composerEntity = new Composer($addon, $composerName);
						$addon->setComposer($composerEntity);
					}

					// Basic info
					$addon->getComposer()->setName($githubComposer->get('name', null) ?? $composerName);
					$addon->getComposer()->setDescription($githubComposer->get('description', null));
					$addon->getComposer()->setType($githubComposer->get('type', null));

					// Keywords
					$keywords = (array) $githubComposer->get('keywords', []);
					$addon->getComposer()->setKeywords($keywords ? implode(',', $keywords) : null);

					// Downloads
					$response = $this->composer->repo($author, $repo);
					if ($response->isOk()) {
						$addon->getComposer()->setDownloads(Arrays::get($response->getJsonBody(), ['package', 'downloads', 'total'], 0));
					}

					// Persist
					$addon->getComposer()->setCrawledAt(new DateTimeImmutable());
					$this->em->persist($addon);
					$this->em->flush();

					// Increase counting
					$counter++;
				} else {
					$output->writeln('Skip (composer) [no composer data]: ' . $addon->getFullname());
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
