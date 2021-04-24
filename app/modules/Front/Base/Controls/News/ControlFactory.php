<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\News;

interface ControlFactory
{

	public function create(): Control;

}
