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

    /** @var array */
    private $data = [];

    /** @var Cache */
    private $cache;

    /** @var PackagesRepository */
    private $packagesRepository;


    /**
     * @param IStorage $storage
     * @param PackagesRepository $packagesRepository
     * @param array $parameters
     */
    function __construct(
        IStorage $storage,
        PackagesRepository $packagesRepository,
        array $parameters = []
    )
    {
        $this->cache = new Cache($storage, 'Portal');
        $this->packagesRepository = $packagesRepository;
        $this->data = $parameters;

        $this->build();
    }


    protected function build()
    {
        $this->cached = $this->cache->load('cached', function (&$dependencies) {
            $dependencies[Cache::EXPIRE] = new DateTime('+1 day');
            $cached = [];

            // Package counts
            $cached['packages'] = $this->packagesRepository->findActive()->count();

            return $cached;
        });
    }

    /*
     * @return int
     */
    public function getPackagesCount()
    {
        return $this->cached['packages'];
    }

    /*
     * @return int
     */
    public function isDebug()
    {
        return $this->data['environment'] === 'development';
    }

    /*
     * @return int
     */
    public function getRevision()
    {
        return $this->data['build']['rev'];
    }

}