<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

trait InjectFactory
{

	protected Factory $factory;

	public function injectFactory(Factory $factory): void
	{
		$this->factory = $factory;
	}

}
