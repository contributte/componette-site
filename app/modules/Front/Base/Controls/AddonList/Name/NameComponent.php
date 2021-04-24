<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Name;

trait NameComponent
{

	private ControlFactory $nameControlFactory;

	public function injectNameControlFactory(ControlFactory $controlFactory): void
	{
		$this->nameControlFactory = $controlFactory;
	}

	public function getNameComponent(): Control
	{
		return $this['name'];
	}

	protected function createComponentName(): Control
	{
		return $this->nameControlFactory->create();
	}

	protected function attachComponentName(Control $component): void
	{
		$this->addComponent($component, 'name');
	}

}
