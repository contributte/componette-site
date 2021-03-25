<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList\Avatar;

use App\Modules\Front\Portal\Base\Controls\AddonList\BaseAddonProps;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Helpers\PropUtils;

class AvatarProps extends BaseAddonProps
{

	public const LINK_TO_GITHUB = 'linkToGithub';

	public const SMALL = 'small';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return PropUtils::merge(parent::define(), [
			self::LINK_TO_GITHUB => Expect::bool(false),
			self::SMALL => Expect::bool(false),
		]);
	}

}
