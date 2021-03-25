<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

trait InjectHandler
{

	protected Handler $handler;

	public function injectHandler(Handler $handler): void
	{
		$this->handler = $handler;
	}

}
