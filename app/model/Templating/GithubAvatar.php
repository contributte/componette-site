<?php

namespace App\Model\Templating;

final class GithubAvatar
{

	const URL = 'https://avatars.githubusercontent.com/%s';

	/**
	 * @param string $user
	 * @phpstan-param array{s?: string|int} $opts
	 */
	public static function generate($user, array $opts = []): string
	{
		$url = self::URL;

		if (isset($opts['s'])) {
			$url .= '?s=' . intval($opts['s']);
		} else {
			$url .= '?s=64';
		}

		return sprintf($url, $user);
	}

}
