<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Name;

use App\Modules\Front\Base\Controls\AddonList\BaseAddonProps;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Helpers\PropUtils;

class NameProps extends BaseAddonProps
{

	public const INVERSE_TAG = 'inverseTag';

	public const LINK_TO_GITHUB = 'linkToGithub';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return PropUtils::merge(parent::define(), [self::INVERSE_TAG => Expect::bool(false), self::LINK_TO_GITHUB => Expect::bool(false)]);
	}

}
