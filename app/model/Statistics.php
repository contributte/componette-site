<?php

namespace App\Model;

use App\Model\ORM\Addon\AddonRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\DateTime;

final class Statistics
{

    /** @var array */
    private $cached = [];

    /** @var Cache */
    private $cache;

    /** @var AddonRepository */
    private $addonRepository;

    /**
     * @param IStorage $storage
     * @param AddonRepository $addonRepository
     */
    public function __construct(IStorage $storage, AddonRepository $addonRepository)
    {
        $this->cache = new Cache($storage, 'Portal');
        $this->addonRepository = $addonRepository;

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
