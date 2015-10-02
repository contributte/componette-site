<?php

namespace App\Model;

use App\Model\ORM\PackagesRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\DateTime;

final class Portal
{

    /** @var array */
    private $cached = [];

    /** @var Cache */
    private $cache;

    /** @var PackagesRepository */
    private $packagesRepository;

    /**
     * @param IStorage $storage
     * @param PackagesRepository $packagesRepository
     */
    function __construct(
        IStorage $storage,
        PackagesRepository $packagesRepository
    )
    {
        $this->cache = new Cache($storage, 'Portal');
        $this->packagesRepository = $packagesRepository;

        $this->build();
    }

    protected function build()
    {
        $this->cached = $this->cache->load('cached', function (&$dependencies) {
            $cached = [];
            $dependencies[Cache::EXPIRE] = new DateTime('+1 day');

            // Package counts
            $cached['packages'] = $this->packagesRepository->findActive()->count();

            return $cached;
        });
    }

    /*
     * @return int
     */
    public function getPackages()
    {
        return $this->cached['packages'];
    }

}