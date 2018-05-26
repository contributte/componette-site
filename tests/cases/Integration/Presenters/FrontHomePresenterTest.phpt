<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';

class FrontHomePresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;


	public function __construct(PresenterTester $presenterTester)
	{
		$this->presenterTester = $presenterTester;
	}


	public function testRedirect()
	{
		$request = $this->presenterTester->createRequest('Front:Home');

		$response = $this->presenterTester->execute($request);

		$response->assertRedirects('Front:Portal:Home');
	}
}


FrontHomePresenterTest::run($containerFactory);
