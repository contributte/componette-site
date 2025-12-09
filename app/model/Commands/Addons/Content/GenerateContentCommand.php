<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons\Content;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Github\Github;
use App\Model\Utils\Validators;
use App\Model\WebServices\Github\GithubService;
use Contributte\Utils\Urls;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateContentCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:content:generate';

	private AddonRepository $addonRepository;

	private GithubService $github;

	private EntityManagerInterface $em;

	public function __construct(AddonRepository $addonRepository, GithubService $github, EntityManagerInterface $em)
	{
		parent::__construct();
		$this->addonRepository = $addonRepository;
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
		if ($input->getOption('rest') === true) {
			$addons = $this->addonRepository->createQueryBuilder('a')
				->leftJoin('a.github', 'g')
				->where('a.state = :state')
				->andWhere('g.contentHtml IS NULL')
				->setParameter('state', Addon::STATE_ACTIVE)
				->getQuery()
				->getResult();
		} else {
			$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE]);
		}

		// DO YOUR JOB ===============================================

		$counter = 0;
		foreach ($addons as $addon) {
			if ($addon->getGithub()) {
				// Raw
				$response1 = $this->github->readme($addon->getAuthor(), $addon->getName(), GithubService::MEDIATYPE_HTML);
				if ($response1->isOk() && is_string($response1->getBody())) {
					// Content
					$addon->getGithub()->setContentRaw($response1->getBody());
				} else {
					$addon->getGithub()->setContentRaw('');
					$output->writeln('Skip (content) [failed download raw content]: ' . $addon->getFullname());
				}

				// HTML
				$response2 = $this->github->readme($addon->getAuthor(), $addon->getName(), GithubService::MEDIATYPE_HTML);
				if ($response2->isOk() && is_string($response2->getBody())) {
					// Content
					$addon->getGithub()->setContentHtml($response2->getBody());
					$this->reformatLinks($addon->getGithub());
				} else {
					$addon->getGithub()->setContentHtml('');
					$output->writeln('Skip (content) [failed download html content]: ' . $addon->getFullname());
				}

				// Persist
				$this->em->persist($addon);
				$this->em->flush();

				// Increase counting
				$counter++;
			}
		}

		$output->writeln(sprintf('Updated %s addons contents', $counter));

		return 0;
	}

	protected function reformatLinks(Github $github): void
	{
		$content = $github->getContentHtml();
		if ($content === null) {
			return;
		}

		// Resolve links
		$content = Strings::replace($content, '#href=\"(.*)\"#iU', function ($matches) use ($github) {
			[, $url] = $matches;

			if (!Validators::isUrl($url)) {
				if (Urls::hasFragment($url)) {
					$url = $github->getLinker()->getFileUrl(null, $url);
				} else {
					$url = $github->getLinker()->getBlobUrl($url);
				}
			}

			return sprintf('href="%s"', $url);
		});

		// Resolve images
		$content = Strings::replace($content, '#img.+src=\"(.*)\"#iU', function ($matches) use ($github) {
			[, $url] = $matches;

			if (!Validators::isUrl($url)) {
				$url = $github->getLinker()->getRawUrl($url);
			}

			return sprintf('img src="%s"', $url);
		});

		$github->setContentHtml($content);
	}

}
