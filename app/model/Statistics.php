<?php declare(strict_types = 1);

namespace App\Model;

use App\Model\Caching\CacheKeys;
use App\Model\Database\ORM\Addon\AddonRepository;
use Contributte\Cache\CacheFactory;
use Nette\Caching\Cache;
use Nette\Utils\DateTime;

final class Statistics
{

	/** @var mixed[] */
	private $cached = [];

	/** @var AddonRepository */
	private $addonRepository;

	/** @var Cache */
	private $cache;

	public function __construct(AddonRepository $addonRepository, CacheFactory $cacheFactory)
	{
		$this->addonRepository = $addonRepository;
		$this->cache = $cacheFactory->create(CacheKeys::FRONT_CONTROLS_STATISTICS);
		$this->build();
	}

	/**
	 * Build cache
	 */
	protected function build(): void
	{
		$this->cached = $this->cache->load('cached', function (&$dependencies) {
			$dependencies[Cache::EXPIRE] = new DateTime('+1 day');
			$cached = [];

			// Addons counts
			$cached['addons'] = $this->addonRepository->findAll()->countStored();

			return $cached;
		});
	}

	public function getAddonsCount(): int
	{
		return $this->cached['addons'];
	}

}
