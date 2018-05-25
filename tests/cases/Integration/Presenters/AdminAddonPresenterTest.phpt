<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Security\SimpleAuthenticator;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityGenerator;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nette\Security\Identity;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class AdminAddonPresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;

	/** @var EntityGenerator */
	private $entityGenerator;

	/** @var Identity */
	private $identity;


	public function __construct(PresenterTester $presenterTester, EntityGenerator $entityGenerator)
	{
		$this->presenterTester = $presenterTester;
		$this->entityGenerator = $entityGenerator;
		$this->identity = new Identity('Admin', SimpleAuthenticator::ROLE_ADMIN);
	}


	public function testRenderWithoutRequiredParameter()
	{
		$request = $this->presenterTester->createRequest('Admin:Addon')
			->withIdentity($this->identity)
			->withParameters(['action' => 'detail']);

		$response = $this->presenterTester->execute($request);
		$response->assertBadRequest(404);
	}


	public function testRenderWithMissingAddon()
	{
		$request = $this->presenterTester->createRequest('Admin:Addon')
			->withIdentity($this->identity)
			->withParameters(['action' => 'detail', 'id' => 123]);

		$response = $this->presenterTester->execute($request);
		$response->assertBadRequest(404);
	}


	public function testRenderOk()
	{
		$addon = $this->entityGenerator->create(Addon::class);

		$request = $this->presenterTester->createRequest('Admin:Addon')
			->withIdentity($this->identity)
			->withParameters(['action' => 'detail', 'id' => $addon->id]);

		$response = $this->presenterTester->execute($request);
		$response->assertRenders(['mangoweb/presenter-tester']);
	}
}


AdminAddonPresenterTest::run($containerFactory);
