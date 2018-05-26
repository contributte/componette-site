<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nextras\Dbal\Connection;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class PortalHomePresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;


	public function __construct(PresenterTester $presenterTester)
	{
		$this->presenterTester = $presenterTester;
	}


	public function testRender()
	{
		$request = $this->presenterTester->createRequest('Front:Portal:Home');

		$response = $this->presenterTester->execute($request);
		$response->assertRenders([
			'pdf'
		]);
	}
}


PortalHomePresenterTest::run($containerFactory);
