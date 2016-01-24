<?php

namespace App\Core\Cache;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class Cacher
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

    public function clean()
    {
        $this->storage->clean([Cache::ALL => TRUE]);
    }

    /**
     * @param array $tags
     */
    public function cleanByTags(array $tags)
    {
        $this->storage->clean([Cache::TAGS => $tags]);
    }

    /**
     * @param int $priority
     */
    public function cleanByPriority($priority)
    {
        $this->storage->clean([Cache::PRIORITY => intval($priority)]);
    }

    /**
     * @param array $conditions
     */
    public function cleanBy($conditions)
    {
        $this->storage->clean($conditions);
    }

}
