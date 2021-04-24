<?php declare (strict_types = 1);

namespace App\Modules\Front\Addon\Controls\FeaturedAddon;

interface ControlFactory
{

	public function create(): Control;

}
