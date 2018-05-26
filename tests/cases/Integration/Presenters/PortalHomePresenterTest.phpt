<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';

class PortalHomePresenterTest extends TestCase
{
	public function testRender()
	{
		Assert::true(false);
	}
}


PortalHomePresenterTest::run($containerFactory);
