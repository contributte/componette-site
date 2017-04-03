<?php

namespace App\Model\Templating\Filters;

use Nette\Utils\Strings;

final class Helpers
{

	/**
	 * @param string $name
	 * @return bool
	 */
	public static function isPhp($name)
	{
		$blacklist = [
			'^php$',
			'^ext-\w',
		];

		foreach ($blacklist as $regex) {
			if (Strings::match($name, sprintf('#%s#', $regex))) {
				return TRUE;
			}
		}

		return FALSE;
	}

}
