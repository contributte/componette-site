<?php declare(strict_types = 1);

namespace AppTests\Integration\Presenters;

use App\Model\Security\SimpleAuthenticator;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\PresenterTester\PresenterTester;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use Tester;

$containerFactory = require __DIR__ . '/../../../bootstrap.mango.php';


class AdminSignPresenterTest extends TestCase
{
	/** @var PresenterTester */
	private $presenterTester;


	public function __construct(PresenterTester $presenterTester)
	{
		$this->presenterTester = $presenterTester;
	}


	public function testRender()
	{
		$request = $this->presenterTester->createRequest('Admin:Sign')
			->withParameters(['action' => 'in']);

		$response = $this->presenterTester->execute($request);
		$response->assertRenders();
	}


	public function testSubmitFormWithUnknownUser()
	{
		$request = $this->presenterTester->createRequest('Admin:Sign')
			->withParameters(['action' => 'in'])
			->withForm('login-form', ['username' => 'Foo', 'password' => 'Bar']);

		$response = $this->presenterTester->execute($request);
		$response->assertRenders(['User "Foo" not found.']);
	}


	/**
	 * @param SimpleAuthenticator|\Mockery\MockInterface $authenticator
	 */
	public function testSubmitFormWithWrongPassword(SimpleAuthenticator $authenticator)
	{
		$authenticator->shouldReceive('authenticate')
			->with(['Foo', 'Bar'])
			->andThrow(new AuthenticationException('Invalid password.', SimpleAuthenticator::INVALID_CREDENTIAL));

		$request = $this->presenterTester->createRequest('Admin:Sign')
			->withParameters(['action' => 'in'])
			->withForm('login-form', ['username' => 'Foo', 'password' => 'Bar']);

		$response = $this->presenterTester->execute($request);
		$response->assertRenders(['Invalid password.']);
	}


	/**
	 * @param SimpleAuthenticator|\Mockery\MockInterface $authenticator
	 */
	public function testSubmitFormOk(SimpleAuthenticator $authenticator)
	{
		$authenticator->shouldReceive('authenticate')
			->with(['Foo', 'Bar'])
			->andReturn(new Identity('Foo', SimpleAuthenticator::ROLE_ADMIN));

		$request = $this->presenterTester->createRequest('Admin:Sign')
			->withParameters(['action' => 'in'])
			->withForm('login-form', ['username' => 'Foo', 'password' => 'Bar']);

		$response = $this->presenterTester->execute($request);
		$response->assertFormValid('login-form');
		$response->assertRedirects('Admin:Home');
	}
}


Tester\Environment::bypassFinals();
AdminSignPresenterTest::run($containerFactory);
