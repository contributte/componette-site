<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Content;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Github\Github;
use App\Model\Utils\Validators;
use App\Model\WebServices\Github\GithubService;
use Contributte\Utils\Urls;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateContentCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:content:generate';

	/** @var AddonRepository */
	private $addonRepository;

	/** @var GithubService */
	private $github;

	public function __construct(AddonRepository $addonRepository, GithubService $github)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
		$this->github = $github;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Generate addons contents');

		$this->addOption(
			'rest',
			null,
			InputOption::VALUE_NONE,
			'Should synchronize only queued addons?'
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		/** @var ICollection|Addon[] $addons */
		$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE]);

		// FILTER PACKAGES ===========================================

		if ($input->getOption('rest') === true) {
			$addons = $addons->findBy(['this->github->contentHtml' => null]);
		}

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			// Raw
			$response1 = $this->github->readme($addon->author, $addon->name, GithubService::MEDIATYPE_HTML);
			if ($response1->isOk()) {
				// Content
				$addon->github->contentRaw = $response1->getBody();
			} else {
				$addon->github->contentRaw = '';
				$output->writeln('Skip (content) [failed download raw content]: ' . $addon->fullname);
			}

			// HTML
			$response2 = $this->github->readme($addon->author, $addon->name, GithubService::MEDIATYPE_HTML);
			if ($response2->isOk()) {
				// Content
				$addon->github->contentHtml = $response2->getBody();
				$this->reformatLinks($addon->github);
			} else {
				$addon->github->contentHtml = '';
				$output->writeln('Skip (content) [failed download html content]: ' . $addon->fullname);
			}

			// Persist
			if ($addon->github->isModified()) {
				$this->addonRepository->persistAndFlush($addon);
			}

			// Increase counting
			$counter++;
		}

		$output->writeln(sprintf('Updated %s addons contents', $counter));

		return 0;
	}

	protected function reformatLinks(Github $github): void
	{
		// Resolve links
		$github->contentHtml = Strings::replace((string)$github->contentHtml, '#href=\"(.*)\"#iU', function ($matches) use ($github) {
			 [$all, $url] = $matches;

			if (!Validators::isUrl($url)) {
				if (Urls::hasFragment($url)) {
					$url = $github->linker->getFileUrl(null, $url);
				} else {
					$url = $github->linker->getBlobUrl($url);
				}
			}

			return sprintf('href="%s"', $url);
		});

		// Resolve images
		$github->contentHtml = Strings::replace($github->contentHtml, '#img.+src=\"(.*)\"#iU', function ($matches) use ($github) {
			 [$all, $url] = $matches;

			if (!Validators::isUrl($url)) {
				$url = $github->linker->getRawUrl($url);
			}

			return sprintf('img src="%s"', $url);
		});
	}

}
