<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

trait SubscribeFormComponent
{

	private ControlFactory $subscribeFormControlFactory;

	public function injectSubscribeFormControlFactory(ControlFactory $controlFactory): void
	{
		$this->subscribeFormControlFactory = $controlFactory;
	}

	public function getSubscribeFormComponent(): Control
	{
		return $this['subscribeForm'];
	}

	protected function createComponentSubscribeForm(): Control
	{
		return $this->subscribeFormControlFactory->create();
	}

	protected function attachComponentSubscribeForm(Control $component): void
	{
		$this->addComponent($component, 'subscribeForm');
	}

}
