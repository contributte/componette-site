<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Avatar;

use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\PropsControl\PropsControl;

/**
 * @DIService(generateComponent=true)
 */
class Control extends PropsControl
{

	protected function getPropsClass(): string
	{
		return AvatarProps::class;
	}

}
