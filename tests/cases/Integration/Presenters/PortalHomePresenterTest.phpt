<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Github\Github;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityGenerator;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nextras\Dbal\Connection;
use Nextras\Orm\Model\Model;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class PortalHomePresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;

	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(PresenterTester $presenterTester, EntityGenerator $entityGenerator)
	{
		$this->presenterTester = $presenterTester;
		$this->entityGenerator = $entityGenerator;
	}


	public function testRender(Model $orm)
	{
		$addon = $this->entityGenerator->create(Addon::class, []);

		$request = $this->presenterTester->createRequest('Front:Portal:Home');

		$response = $this->presenterTester->execute($request);
		$response->assertRenders([
			'Testx addon',
			'pdf',
		]);
		$response->assertNotRenders(['foox']);
	}


	public function testAddAddon(AddonRepository $addonRepository)
	{
		$request = $this->presenterTester->createRequest('Front:Portal:Home')
			->withForm('modal-form', [
				'addon' => 'http://www.github.com/nextras/migrations',
			]);

		$response = $this->presenterTester->execute($request);
		$response->assertFormValid('modal-form');
		$response->assertRedirects('Front:Portal:Home');

		$addons = $addonRepository->findAll();
		Assert::count(1, $addons);
		$addon = $addons->fetch();
		assert($addon instanceof Addon);

		Assert::same('nextras', $addon->author);
		Assert::same('migrations', $addon->name);
	}


	public function testAddonsFormHasErrors()
	{
		$request = $this->presenterTester->createRequest('Front:Portal:Home')
			->withForm('modal-form', [
				'addon' => 'http://www.google.com/nextras/migrations',
			]);

		$response = $this->presenterTester->execute($request);
		$response->assertFormHasErrors('modal-form', ['Only GitHub urls are allowed']);
	}
}


PortalHomePresenterTest::run($containerFactory);
