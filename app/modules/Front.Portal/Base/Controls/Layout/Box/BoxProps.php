<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Layout\Box;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\PropsControl\Helpers\PropTypes;
use Wavevision\PropsControl\Props;

class BoxProps extends Props
{

	public const ATTRIBUTES = 'attributes';

	public const CLASS_NAMES = 'classNames';

	public const CONTENT = 'content';

	/**
	 * @return Schema[]
	 */
	protected function define(): array
	{
		return [
			self::ATTRIBUTES => Expect::arrayOf(Expect::string())->default([]),
			self::CLASS_NAMES => Expect::arrayOf(Expect::string())->default([]),
			self::CONTENT => PropTypes::html()->nullable(),
		];
	}

}
