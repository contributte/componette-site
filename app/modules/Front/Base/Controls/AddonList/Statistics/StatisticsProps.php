<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Statistics;

use App\Modules\Front\Base\Controls\AddonList\BaseAddonProps;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Helpers\PropUtils;

class StatisticsProps extends BaseAddonProps
{

	public const FEATURED = 'featured';

	public const INLINE = 'inline';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return PropUtils::merge(parent::define(), [
			self::FEATURED => Expect::bool(false),
			self::INLINE => Expect::bool(false),
		]);
	}

}
