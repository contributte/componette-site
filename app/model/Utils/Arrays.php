<?php declare(strict_types = 1);

namespace App\Model\Utils;

use Nette\Utils\ArrayHash;

final class Arrays
{

	/**
	 * @param mixed[]|ArrayHash $array
	 * @return mixed[]
	 */
	public static function ensure($array): array
	{
		return array_map(function ($item) {
			return is_iterable($item) ? self::ensure($item) : $item;
		}, (array) $array);
	}

}
