<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nextras\Dbal\Connection;
use Nextras\Orm\Model\Model;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class PortalHomePresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;


	public function __construct(PresenterTester $presenterTester)
	{
		$this->presenterTester = $presenterTester;
	}


	public function testRender(Model $orm)
	{
		$addon = new Addon();
		$addon->name = 'Testx addon';
		$addon->author = 'nextras';
		$addon->state = Addon::STATE_ACTIVE;
		$addon->type = Addon::TYPE_COMPOSER;
		$github = new Github();
		$addon->github = $github;
		$orm->persistAndFlush($addon);

		$request = $this->presenterTester->createRequest('Front:Portal:Home');

		$response = $this->presenterTester->execute($request);
		$response->assertRenders([
			'Testx addon',
			'pdf',
		]);
		$response->assertNotRenders(['foox']);
	}
}


PortalHomePresenterTest::run($containerFactory);
