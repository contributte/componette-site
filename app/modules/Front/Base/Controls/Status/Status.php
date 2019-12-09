<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Status;

use App\Model\Caching\CacheKeys;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\UI\BaseControl;
use App\Model\WebServices\Github\GithubService;
use Contributte\Cache\CacheFactory;
use Nette\Caching\Cache;
use Nette\Utils\DateTime;

final class Status extends BaseControl
{

	/** @var AddonRepository */
	private $addonRepository;

	/** @var GithubService */
	private $github;

	/** @var Cache */
	private $cache;

	public function __construct(
		AddonRepository $addonRepository,
		GithubService $github,
		CacheFactory $cacheFactory
	)
	{
		$this->addonRepository = $addonRepository;
		$this->github = $github;
		$this->cache = $cacheFactory->create(CacheKeys::FRONT_CONTROLS_STATUS);
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * Render status fragments
	 */
	public function render(): void
	{
		// Load data
		$status = $this->cache->load('data', function (&$dependencies) {
			$dependencies[Cache::EXPIRE] = new DateTime('+30 minutes');
			$dependencies[Cache::TAGS] = ['status', 'status.page'];

			$status = [];

			// Build addons status ===========================================
			$status['addons']['active']['composer'] = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_COMPOSER])->countStored();
			$status['addons']['active']['bower'] = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_BOWER])->countStored();
			$status['addons']['active']['untype'] = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_UNTYPE])->countStored();
			$status['addons']['active']['unknown'] = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE, 'type' => Addon::TYPE_UNKNOWN])->countStored();
			$status['addons']['active']['total'] = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE])->countStored();

			$status['addons']['queued']['composer'] = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED, 'type' => Addon::TYPE_COMPOSER])->countStored();
			$status['addons']['queued']['bower'] = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED, 'type' => Addon::TYPE_BOWER])->countStored();
			$status['addons']['queued']['untype'] = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED, 'type' => Addon::TYPE_UNTYPE])->countStored();
			$status['addons']['queued']['unknown'] = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED, 'type' => Addon::TYPE_UNKNOWN])->countStored();
			$status['addons']['queued']['total'] = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED])->countStored();

			$status['addons']['archived']['composer'] = $this->addonRepository->findBy(['state' => Addon::STATE_ARCHIVED, 'type' => Addon::TYPE_COMPOSER])->countStored();
			$status['addons']['archived']['bower'] = $this->addonRepository->findBy(['state' => Addon::STATE_ARCHIVED, 'type' => Addon::TYPE_BOWER])->countStored();
			$status['addons']['archived']['untype'] = $this->addonRepository->findBy(['state' => Addon::STATE_ARCHIVED, 'type' => Addon::TYPE_UNTYPE])->countStored();
			$status['addons']['archived']['unknown'] = $this->addonRepository->findBy(['state' => Addon::STATE_ARCHIVED, 'type' => Addon::TYPE_UNKNOWN])->countStored();
			$status['addons']['archived']['total'] = $this->addonRepository->findBy(['state' => Addon::STATE_ARCHIVED])->countStored();

			$status['addons']['total']['composer'] = $this->addonRepository->findBy(['type' => Addon::TYPE_COMPOSER])->countStored();
			$status['addons']['total']['bower'] = $this->addonRepository->findBy(['type' => Addon::TYPE_BOWER])->countStored();
			$status['addons']['total']['untype'] = $this->addonRepository->findBy(['type' => Addon::TYPE_UNTYPE])->countStored();
			$status['addons']['total']['unknown'] = $this->addonRepository->findBy(['type' => Addon::TYPE_UNKNOWN])->countStored();
			$status['addons']['total']['total'] = $this->addonRepository->findAll()->countStored();

			// Build github status =============================================

			$response = $this->github->limit();
			if ($response->isOk()) {
				$limit = $response->getJsonBody();

				$status['github']['core']['limit'] = $limit['resources']['core']['limit'];
				$status['github']['core']['remaining'] = $limit['resources']['core']['remaining'];
				$status['github']['core']['reset'] = DateTime::from($limit['resources']['core']['reset']);

				$status['github']['search']['limit'] = $limit['resources']['search']['limit'];
				$status['github']['search']['remaining'] = $limit['resources']['search']['remaining'];
				$status['github']['search']['reset'] = DateTime::from($limit['resources']['search']['reset']);
			}

			return $status;
		});

		// Fill template
		$this->template->status = $status;

		// Render template
		$this->template->setFile(__DIR__ . '/templates/status.latte');
		$this->template->render();
	}

}
