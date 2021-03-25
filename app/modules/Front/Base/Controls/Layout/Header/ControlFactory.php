<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header;

interface ControlFactory
{

	public function create(): Control;

}
