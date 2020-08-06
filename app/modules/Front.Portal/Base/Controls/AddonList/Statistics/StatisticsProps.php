<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList\Statistics;

use App\Modules\Front\Portal\Base\Controls\AddonList\BaseAddonProps;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Helpers\PropUtils;

class StatisticsProps extends BaseAddonProps
{

	public const INLINE = 'inline';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return PropUtils::merge(parent::define(), [self::INLINE => Expect::bool(false)]);
	}

}
