<?php

namespace App\Model\Routing\Helpers;

use App\Model\ORM\PackagesRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class PackagesHelper
{

    /** @var PackagesRepository */
    private $packages;

    /** @var Cache */
    private $cache;

    /** @var array */
    private $data = [
        'packages' => [],
        'owners' => [],
    ];

    /**
     * @param PackagesRepository $packages
     * @param IStorage $storage
     */
    function __construct(
        PackagesRepository $packages,
        IStorage $storage)
    {
        $this->packages = $packages;
        $this->cache = new Cache($storage, 'Router');

        $this->build();
    }

    protected function build()
    {
        $this->data = $this->cache->load('routes', function () {
            $data = ['packages' => [], 'owners' => []];

            foreach ($this->packages->findAll() as $package) {
                $data['packages'][$package->id] = strtolower($package->metadata->name);
                $data['owners'][strtolower($package->metadata->owner)] = strtolower($package->metadata->owner);
            }

            return $data;
        });
    }

    /**
     * PACKAGE *****************************************************************
     */

    /**
     * @param string $slug
     * @return int|NULL
     */
    public function packageIn($slug)
    {
        $package = array_search(strtolower($slug), $this->data['packages']);
        return $package ? $package : NULL;
    }

    /**
     * @param int $id
     * @return string|NULL
     */
    public function packageOut($id)
    {
        if (isset($this->data['packages'][$id])) {
            return strtolower($this->data['packages'][$id]);
        }
        return NULL;
    }

    /**
     * OWNER *******************************************************************
     */

    /**
     * @param string $slug
     * @return int|NULL
     */
    public function ownerIn($slug)
    {
        $slug = strtolower($slug);
        if (isset($this->data['owners'][$slug])) {
            return strtolower($this->data['owners'][$slug]);
        }
        return NULL;
    }

    /**
     * @param string $string
     * @return string
     */
    public function ownerOut($slug)
    {
        $slug = strtolower($slug);
        if (isset($this->data['owners'][$slug])) {
            return strtolower($this->data['owners'][$slug]);
        }
        return NULL;
    }
}
