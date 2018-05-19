<?php declare(strict_types = 1);

namespace App\Model\Security;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;

final class SimpleAuthenticator implements IAuthenticator
{

	// Roles
	public const ROLE_ADMIN = 'admin';

	/** @var string[] */
	private $userlist;

	/**
	 * @param string[] $userlist
	 */
	public function __construct(array $userlist)
	{
		$this->userlist = $userlist;
	}

	/**
	 * @param string[] $credentials
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials): IIdentity
	{
		[$username, $password] = $credentials;
		foreach ($this->userlist as $_name => $_pass) {
			if (strcasecmp($_name, $username) === 0) {
				if (Passwords::verify((string) $password, $_pass)) {
					return new Identity($_name, self::ROLE_ADMIN);
				} else {
					throw new AuthenticationException('Invalid password.', self::INVALID_CREDENTIAL);
				}
			}
		}

		throw new AuthenticationException('User "' . $username . '" not found.', self::IDENTITY_NOT_FOUND);
	}

}
