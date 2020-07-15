<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\PropsControl\Props;

class SvgProps extends Props
{

	public const ALT = 'alt';

	public const CLASS_NAME = 'className';

	public const FILL = 'fill';

	public const IMAGE = 'image';

	public const SIZE = 'size';

	public const TYPE = 'type';

	public const DEFAULT_TYPE = 'logos';

	/**
	 * @return Schema[]
	 */
	protected function define(): array
	{
		return [
			self::ALT => Expect::string()->nullable(),
			self::CLASS_NAME => Expect::string()->nullable(),
			self::FILL => Expect::string()->nullable(),
			self::IMAGE => Expect::string()->required(),
			self::SIZE => Expect::int()->nullable(),
			self::TYPE => Expect::string(self::DEFAULT_TYPE),
		];
	}

}
