<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\PropsControl\Props;

class SocialLinksProps extends Props
{

	public const LINKS = 'links';

	/**
	 * @return Schema[]
	 */
	protected function define(): array
	{
		return [
			self::LINKS => Expect::listOf(SocialLink::class),
		];
	}

}
