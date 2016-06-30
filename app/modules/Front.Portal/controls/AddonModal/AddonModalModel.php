<?php

namespace App\Modules\Front\Portal\Controls\AddonModal;

use App\Core\Cache\CacheProvider;
use App\Model\Cache\CacheKeys;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Tag\TagRepository;
use Nette\Caching\Cache;

final class AddonModalModel
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var TagRepository */
    private $tagRepository;

    /** @var CacheProvider */
    private $cacheProvider;

    /**
     * @param CacheProvider $cacheProvider
     * @param AddonRepository $addonRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(
        CacheProvider $cacheProvider,
        AddonRepository $addonRepository,
        TagRepository $tagRepository
    )
    {
        $this->addonRepository = $addonRepository;
        $this->tagRepository = $tagRepository;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @return Addon
     */
    public function createAddon()
    {
        $addon = new Addon();
        $this->addonRepository->attach($addon);

        return $addon;
    }

    /**
     * @param Addon $addon
     * @return Addon
     */
    public function persist(Addon $addon)
    {
        return $this->addonRepository->persistAndFlush($addon);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $cache = $this->cacheProvider->create(CacheKeys::FRONT_CONTROLS_ADDON_MODAL);

        return $cache->load('tags', function (&$dependencies) {
            $dependencies[Cache::EXPIRATION] = '+1 week';

            return $this->tagRepository->fetchPairs();
        });
    }
}
