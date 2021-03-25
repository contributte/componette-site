<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header\Menu;

use Nette\Schema\Expect;
use Wavevision\PropsControl\Props;

class MenuProps extends Props
{

	public const LINKS = 'links';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return [
			self::LINKS => Expect::listOf(MenuLink::class),
		];
	}

}
