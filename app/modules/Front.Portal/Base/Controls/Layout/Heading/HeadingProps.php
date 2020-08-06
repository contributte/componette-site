<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Layout\Heading;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\PropsControl\Helpers\PropTypes;
use Wavevision\PropsControl\Props;

class HeadingProps extends Props
{

	public const CONTENT = 'content';

	public const TYPE = 'type';

	public const TYPE_H1 = 'h1';

	public const TYPE_H2 = 'h2';

	public const TYPE_H3 = 'h3';

	/**
	 * @return Schema[]
	 */
	protected function define(): array
	{
		return [
			self::CONTENT => PropTypes::pureRenderable()->required(),
			self::TYPE => Expect::anyOf(self::TYPE_H1, self::TYPE_H2, self::TYPE_H3)->default(self::TYPE_H2),
		];
	}

}
