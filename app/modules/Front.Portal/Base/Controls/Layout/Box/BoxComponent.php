<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Layout\Box;

trait BoxComponent
{

	private ControlFactory $boxControlFactory;

	public function injectBoxControlFactory(ControlFactory $controlFactory): void
	{
		$this->boxControlFactory = $controlFactory;
	}

	public function getBoxComponent(): Control
	{
		return $this['box'];
	}

	protected function createComponentBox(): Control
	{
		return $this->boxControlFactory->create();
	}

	protected function attachComponentBox(Control $component): void
	{
		$this->addComponent($component, 'box');
	}

}
