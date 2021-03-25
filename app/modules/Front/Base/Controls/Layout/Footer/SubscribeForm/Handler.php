<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

use Nette\Forms\Form;
use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateInject=true)
 */
class Handler
{

	use SmartObject;

	public function process(Form $form): void
	{
		bdump($form->values);
	}

}
