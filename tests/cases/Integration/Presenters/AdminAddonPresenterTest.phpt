<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Security\SimpleAuthenticator;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityGenerator;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nette\Security\Identity;
use Tester\Assert;

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


	public function testAddTags()
	{
		/** @var Addon $addon */
		$addon = $this->entityGenerator->create(Addon::class);
		Assert::same([], $addon->tags->get()->fetchPairs(null, 'name'));

		/** @var Tag[] $tags */
		$tags = $this->entityGenerator->createList(Tag::class, 5);

		$request = $this->presenterTester->createRequest('Admin:Addon')
			->withIdentity($this->identity)
			->withParameters(['action' => 'detail', 'id' => $addon->id])
			->withForm('addonForm', [
				'author' => $addon->author,
				'name' => $addon->name,
				'tags' => [$tags[0]->id, $tags[2]->id],
			]);

		$response = $this->presenterTester->execute($request);
		$response->assertFormValid('addonForm');
		$response->assertRedirects('Admin:Addon');

		$this->entityGenerator->refreshAll(); // TODO: why is this not necessary?
		Assert::same(['tag1', 'tag3'], $addon->tags->get()->fetchPairs(null, 'name'));
	}
}


AdminAddonPresenterTest::run($containerFactory);
