<?php declare(strict_types = 1);

namespace AppTests\Integration\Console;

use App\Model\Commands\Addons\Github\SynchronizeReleasesCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\WebServices\Github\GithubService;
use Contributte\Http\Curl\Response;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityGenerator;
use Mockery\MockInterface;
use Nextras\Orm\Model\Model;
use Symfony\Component\Console\Tester\CommandTester;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class GithubReleasesSynchronizeCommandTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	/**
	 * @param GithubService|MockInterface $githubService
	 */
	public function testSynchronize(SynchronizeReleasesCommand $command, Model $orm, GithubService $githubService)
	{
		$githubService->shouldReceive('allReleases')
			->with('nextras', 'migrations', 'html')
			->andReturn([
				\Mockery::mock(Response::class)
					->shouldReceive('getJsonBody')
					->andReturn([
						[
							'id' => 123,
							'name' => 'Hello world',
							'tag_name' => 'v1.0',
							'draft' => false,
							'prerelease' => false,
							'created_at' => '2018-05-25',
							'published_at' => '2018-05-25',
							'body_html' => 'abcd',
						],
					])->getMock()
			]);

		$addon = $this->entityGenerator->create(Addon::class, [
			'name' => 'migrations',
			'author' => 'nextras',
			'github' => [
				'stars' => 3,
			],
			'tags' => [
				['name' => 'abc'],
				['name' => 'xyz'],
			],
		]);
		assert($addon instanceof Addon);
		Assert::same(3, $addon->github->stars);
		Assert::same(['abc', 'xyz'], $addon->tags->get()->fetchPairs(NULL, 'name'));

		$commandTester = new CommandTester($command);
		$commandTester->execute(['type' => 'composer']);
		$orm->refreshAll();

		$releases = $addon->github->releases->get();
		Assert::count(1, $releases);
		$release = $releases->fetch();
		assert($release instanceof GithubRelease);

		Assert::same('Hello world', $release->name);
	}
}


GithubReleasesSynchronizeCommandTest::run($containerFactory);
