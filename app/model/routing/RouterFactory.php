<?php

namespace App\Model\Routing;

use App\Model\Routing\Helpers\PackagesHelper;
use Nette\Application\IRouter;
use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{

    /** @var PackagesHelper @inject */
    public $packagesRouterHelper;

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

        // FRONT ===========================================

        $router[] = $front = new RouteList('Front');
        $front[] = new Route('<slug [a-zA-Z0-9\-]+/[a-zA-Z0-9\-]+>/', [
            'presenter' => 'Package',
            'action' => 'detail',
            'slug' => [
                Route::FILTER_IN => [$this->packagesRouterHelper, 'packageIn'],
                Route::FILTER_OUT => [$this->packagesRouterHelper, 'packageOut'],
            ],
        ]);
        $front[] = new Route('<slug [a-zA-Z0-9\-]+>/', [
            'presenter' => 'List',
            'action' => 'owner',
            'slug' => [
                Route::FILTER_IN => [$this->packagesRouterHelper, 'ownerIn'],
                Route::FILTER_OUT => [$this->packagesRouterHelper, 'ownerOut'],
            ],
        ]);
        $front[] = new Route('all/', 'List:default');
        $front[] = new Route('search/', 'List:search');
        $front[] = new Route('status/', 'Status:default');
        $front[] = new Route('<presenter>/<action>', 'Home:default');

        return $router;
    }
}
