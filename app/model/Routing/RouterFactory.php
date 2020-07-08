<?php declare(strict_types = 1);

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

	public function create(bool $consoleMode = false): IRouter
	{
		if ($consoleMode) {
			return $this->createCli();
		} else {
			return $this->createWeb();
		}
	}

	protected function createCli(): RouteList
	{
		$router = new RouteList('Cli');
		$router[] = new CliRouter(['action' => 'Cli:hi']);

		return $router;
	}

	protected function createWeb(): RouteList
	{
		$router = new RouteList();

		// FRONT ===========================================

		$router[] = $front = new RouteList('Front');
		$front[] = new Route('sitemap.xml', 'Generator:sitemap');
		$front[] = new Route('opensearch.xml', 'Generator:opensearch');

		// FRONT.API =======================================

		$front[] = $api = new RouteList('Api');
		$api[] = new Route('api/v1/opensearch/suggest', 'OpenSearch:suggest');

		// FRONT.PORTAL ====================================

		$front[] = $portal = new RouteList('Portal');
		$portal[] = new Route('rss/new.xml', 'Rss:newest', Route::ONE_WAY);
		$portal[] = new Route('rss/latest[!.xml]', 'Rss:newest');
		$portal[] = new Route('rss/<author [a-zA-Z0-9\-\.]+>[!.xml]', 'Rss:author');

		$portal[] = new Route('<slug [a-zA-Z0-9\-\.]+/[a-zA-Z0-9\-\.]+>/', [
			'presenter' => 'Addon',
			'action' => 'detail',
			'slug' => [
				Route::FILTER_IN => [$this->addonsHelper, 'addonIn'],
				Route::FILTER_OUT => [$this->addonsHelper, 'addonOut'],
			],
		]);
		$portal[] = new Route('<slug [a-zA-Z0-9\-\.]+>/', [
			'presenter' => 'Index',
			'action' => 'author',
			'slug' => [
				Route::FILTER_IN => [$this->addonsHelper, 'authorIn'],
				Route::FILTER_OUT => [$this->addonsHelper, 'authorOut'],
			],
		]);
		$portal[] = new Route('', 'Home:default');
		$portal[] = new Route('all/', 'Index:all');
		$portal[] = new Route('search/', 'Index:search');
		$portal[] = new Route('search/<tag>', 'Index:tag');

		// COMMON SCHEME
		$front[] = new Route('<presenter>/<action>', 'Home:default');

		return $router;
	}

}
