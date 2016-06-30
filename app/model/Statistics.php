<?php

namespace App\Model;

use App\Core\Cache\CacheProvider;
use App\Model\Cache\CacheKeys;
use App\Model\ORM\Addon\AddonRepository;
use Nette\Caching\Cache;
use Nette\Utils\DateTime;

final class Statistics
{

    /** @var array */
    private $cached = [];

    /** @var AddonRepository */
    private $addonRepository;

    /** @var Cache */
    private $cache;

    /**
     * @param AddonRepository $addonRepository
     * @param CacheProvider $cacheProvider
     */
    public function __construct(AddonRepository $addonRepository, CacheProvider $cacheProvider)
    {
        $this->addonRepository = $addonRepository;
        $this->cache = $cacheProvider->create(CacheKeys::FRONT_CONTROLS_STATISTICS);
        $this->build();
    }

    /**
     * Build cache
     */
    protected function build()
    {
        $this->cached = $this->cache->load('cached', function (&$dependencies) {
            $dependencies[Cache::EXPIRE] = new DateTime('+1 day');
            $cached = [];

            // Addons counts
            $cached['addons'] = $this->addonRepository->findActive()->countStored();

            return $cached;
        });
    }

    /*
     * @return int
     */
    public function getAddonsCount()
    {
        return $this->cached['addons'];
    }

}
