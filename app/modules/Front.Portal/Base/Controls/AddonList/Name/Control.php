<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList\Name;

use App\Model\UI\BasePropsControl;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BasePropsControl
{

	protected function getPropsClass(): string
	{
		return NameProps::class;
	}

}
