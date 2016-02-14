<?php

namespace App\Model\Routing;

use App\Model\Routing\Helpers\AddonsHelper;
use Nette\Application\IRouter;
use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Http\Request;

final class RouterFactory
{

    /** @var AddonsHelper @inject */
    public $addonsHelper;

    /** @var Request @inject */
    public $httpRequest;

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

        if ($this->httpRequest->isSecured()) {
            Route::$defaultFlags = Route::SECURED;
        }

        // ADMIN ===========================================

        $router[] = $admin = new RouteList('Admin');
        $admin[] = new Route('admin/<presenter>/<action>[/<id>]', 'Home:default');

        // FRONT ===========================================

        $router[] = $front = new RouteList('Front');
        $front[] = new Route('sitemap.xml', 'Generator:sitemap');
        $front[] = new Route('opensearch.xml', 'Generator:opensearch');
        $front[] = new Route('rss/new.xml', 'Rss:newest');

        $front[] = new Route('<slug [a-zA-Z0-9\-\.]+/[a-zA-Z0-9\-\.]+>/', [
            'presenter' => 'Addon',
            'action' => 'detail',
            'slug' => [
                Route::FILTER_IN => [$this->addonsHelper, 'addonIn'],
                Route::FILTER_OUT => [$this->addonsHelper, 'addonOut'],
            ],
        ]);
        $front[] = new Route('<slug [a-zA-Z0-9\-\.]+>/', [
            'presenter' => 'List',
            'action' => 'owner',
            'slug' => [
                Route::FILTER_IN => [$this->addonsHelper, 'ownerIn'],
                Route::FILTER_OUT => [$this->addonsHelper, 'ownerOut'],
            ],
        ]);
        $front[] = new Route('all/', 'List:default');
        $front[] = new Route('search/', 'List:search');
        $front[] = new Route('search/<tag>', 'List:tag');
        $front[] = new Route('status/', 'Status:default');
        $front[] = new Route('<presenter>/<action>', 'Home:default');

        return $router;
    }
}
