<?php

namespace App\Core\Cache;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class CacheProvider
{

    /** @var IStorage */
    private $storage;

    /**
     * @param IStorage $storage
     */
    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $namespace
     * @return Cache
     */
    public function create($namespace)
    {
        return new Cache($this->storage, $namespace);
    }

}
