<?php

namespace App\Model\Packages\Content;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class ContentManager
{

    /** @var Cache */
    private $cache;

    /**
     * @param IStorage $storage
     */
    function __construct(IStorage $storage)
    {
        $this->cache = new Cache($storage, 'Repos.Readmes');
    }

    /**
     * @param string $hash
     * @param callable $fallback [optionall]
     * @return mixed
     */
    public function load($hash, $fallback = NULL)
    {
        return $this->cache->load($hash, $fallback);
    }

    /**
     * @param string $hash
     * @param mixed $data
     * @param array $dependencies
     * @return mixed
     */
    public function save($hash, $data, array $dependencies = [])
    {
        return $this->cache->save($hash, $data, $dependencies);
    }

}