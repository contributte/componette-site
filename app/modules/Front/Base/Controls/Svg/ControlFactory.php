<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

interface ControlFactory
{

	public function create(): Control;

}
