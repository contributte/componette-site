<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Avatar;

use Wavevision\PropsControl\PropsControl;

class Control extends PropsControl
{

	protected function getPropsClass(): string
	{
		return AvatarProps::class;
	}

}
