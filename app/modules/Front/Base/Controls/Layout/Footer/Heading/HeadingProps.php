<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\Heading;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\PropsControl\Props;

class HeadingProps extends Props
{

	public const TEXT = 'text';

	/**
	 * @return Schema[]
	 */
	protected function define(): array
	{
		return [
			self::TEXT => Expect::string()->required(),
		];
	}

}
