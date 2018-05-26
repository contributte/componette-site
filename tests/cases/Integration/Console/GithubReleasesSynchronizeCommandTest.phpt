<?php declare(strict_types = 1);

namespace AppTests\Integration\Console;

use App\Model\Commands\Addons\Github\SynchronizeReleasesCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\WebServices\Github\GithubClient;
use App\Model\WebServices\Github\GithubService;
use Contributte\Http\Curl\Response;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mockery\MockInterface;
use Nextras\Orm\Model\Model;
use Symfony\Component\Console\Tester\CommandTester;
use Tester\Assert;
use Tester\Environment;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class GithubReleasesSynchronizeCommandTest extends TestCase
{
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
		$addon = new Addon();
		$addon->name = 'migrations';
		$addon->author = 'nextras';
		$addon->state = Addon::STATE_ACTIVE;
		$addon->type = Addon::TYPE_COMPOSER;
		$github = new Github();
		$addon->github = $github;
		$orm->persistAndFlush($addon);

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
