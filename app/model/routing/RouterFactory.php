<?php

namespace App\Model\Routing;

use Nette\Application\IRouter;
use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{

    /**
     * @return IRouter
     */
    public function create()
    {
        if (PHP_SAPI === 'cli') {
            return $this->createCli();
        } else {
            return $this->createWeb();
        }
    }

    /**
     * @return RouteList
     */
    protected function createCli()
    {
        $router = new RouteList('Cli');
        $router[] = new CliRouter(['action' => 'Cli:hi']);
        return $router;
    }

    /**
     * @return RouteList
     */
    protected function createWeb()
    {
        $router = new RouteList();

        $router[] = $front = new RouteList('Front');
        $front[] = new Route('package/<id [0-9]+>', 'Package:detail');
        $front[] = new Route('a/<owner [a-zA-Z0-9\-]+>', 'List:owner');
        $front[] = new Route('t/<tag [a-zA-Z0-9\-]+>', 'List:tag');
        $front[] = new Route('<presenter>/<action>[/<id>]', 'List:default');
        return $router;
    }
}
