<?php declare(strict_types = 1);

namespace App\Model\Routing;

use App\Model\Routing\RouterHelper;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Http\Request;

final class RouterFactory
{

	/** @var RouterHelper @inject */
	public $addonsHelper;

	/** @var Request @inject */
	public $httpRequest;

	public function create(): RouteList
	{
		$router = new RouteList();

		$router->withModule('Front')
			->addRoute('sitemap.xml', 'Generator:sitemap')
			->addRoute('opensearch.xml', 'Generator:opensearch');

		$router->withModule('Api')
			->addRoute('api/v1/opensearch/suggest', 'OpenSearch:suggest');

		$router->withModule('Portal')
			->addRoute('rss/new.xml', 'Rss:newest', Route::ONE_WAY)
			->addRoute('rss/latest[!.xml]', 'Rss:newest')
			->addRoute('rss/<author [a-zA-Z0-9\-\.]+>[!.xml]', 'Rss:author')
			->addRoute('<slug [a-zA-Z0-9\-\.]+/[a-zA-Z0-9\-\.]+>/', [
				'presenter' => 'Addon',
				'action' => 'detail',
				'slug' => [
					Route::FILTER_IN => [$this->addonsHelper, 'addonIn'],
					Route::FILTER_OUT => [$this->addonsHelper, 'addonOut'],
				],
			])
			->addRoute('<slug [a-zA-Z0-9\-\.]+>/', [
				'presenter' => 'Index',
				'action' => 'author',
				'slug' => [
					Route::FILTER_IN => [$this->addonsHelper, 'authorIn'],
					Route::FILTER_OUT => [$this->addonsHelper, 'authorOut'],
				],
			])
			->addRoute('', 'Home:default')
			->addRoute('all/', 'Index:all')
			->addRoute('search/', 'Index:search')
			->addRoute('search/<tag>', 'Index:tag');

		return $router;
	}

}
