<?php declare(strict_types = 1);

namespace App\Model\Utils;

final class Arrays
{

	/**
	 * @param mixed[]|iterable $array
	 * @return mixed[]
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public static function ensure($array): array
	{
		return array_map(function ($item) {
			return is_iterable($item) ? self::ensure($item) : $item;
		}, (array) $array);
	}

}
