<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header;

trait HeaderComponent
{

	/**
	 * @var ControlFactory
	 */
	private ControlFactory $headerControlFactory;

	public function injectHeaderControlFactory(ControlFactory $controlFactory): void
	{
		$this->headerControlFactory = $controlFactory;
	}

	public function getHeaderComponent(): Control
	{
		return $this['header'];
	}

	protected function createComponentHeader(): Control
	{
		return $this->headerControlFactory->create();
	}

	protected function attachComponentHeader(Control $component): void
	{
		$this->addComponent($component, 'header');
	}

}
