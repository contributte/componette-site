<?php

namespace App\Model;

use App\Model\ORM\PackagesRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\DI\Helpers;
use Nette\Utils\Arrays;
use Nette\Utils\DateTime;

final class Portal
{

    /** @var array */
    private $cached = [];

    /** @var array */
    private $parameters = [];

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
        $this->parameters = $parameters;

        $this->build();
    }


    protected function build()
    {
        $this->cached = $this->cache->load('cached', function (&$dependencies) {
            $dependencies[Cache::EXPIRE] = new DateTime('+1 day');
            $cached = [];

            // Package counts
            $cached['packages'] = $this->packagesRepository->findActive()->countStored();

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
        return $this->parameters['environment'] === 'development';
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = NULL)
    {
        if (func_num_args() > 1) {
            return Arrays::get($this->parameters, $name, $default);
        } else {
            return Arrays::get($this->parameters, $name);
        }
    }

    /**
     * @param string $name
     * @param boolean $recursive
     * @return mixed
     */
    public function expand($name, $recursive = FALSE)
    {
        return Helpers::expand("%$name%", $this->parameters, $recursive);
    }

}
