<?php

namespace App\Model\Templating;

final class GithubAvatar
{

	const URL = 'https://avatars.githubusercontent.com/%s';

	/**
	 * @param string $user
	 * @return string
	 */
	public static function generate($user): string
	{
		return sprintf(self::URL, $user);
	}

}
