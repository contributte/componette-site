<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Props;

abstract class BaseAddonProps extends Props
{

	public const ADDON = 'addon';

	/**
	 * @return array<mixed>
	 */
	protected function define(): array
	{
		return [
			self::ADDON => Expect::type(Addon::class)->required(),
		];
	}

}
