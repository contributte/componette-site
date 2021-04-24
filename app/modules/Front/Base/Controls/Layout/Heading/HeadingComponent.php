<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Heading;

trait HeadingComponent
{

	private ControlFactory $headingControlFactory;

	public function injectHeadingControlFactory(ControlFactory $controlFactory): void
	{
		$this->headingControlFactory = $controlFactory;
	}

	public function getHeadingComponent(): Control
	{
		return $this['heading'];
	}

	protected function createComponentHeading(): Control
	{
		return $this->headingControlFactory->create();
	}

	protected function attachComponentHeading(Control $component): void
	{
		$this->addComponent($component, 'heading');
	}

}
