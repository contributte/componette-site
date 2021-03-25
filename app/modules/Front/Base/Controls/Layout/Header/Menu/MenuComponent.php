<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header\Menu;

trait MenuComponent
{

	private ControlFactory $menuControlFactory;

	public function injectMenuControlFactory(ControlFactory $controlFactory): void
	{
		$this->menuControlFactory = $controlFactory;
	}

	public function getMenuComponent(): Control
	{
		return $this['menu'];
	}

	protected function createComponentMenu(): Control
	{
		return $this->menuControlFactory->create();
	}

	protected function attachComponentMenu(Control $component): void
	{
		$this->addComponent($component, 'menu');
	}

}
