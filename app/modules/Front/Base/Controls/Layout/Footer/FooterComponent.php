<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer;

trait FooterComponent
{

	private ControlFactory $footerControlFactory;

	public function injectFooterControlFactory(ControlFactory $controlFactory): void
	{
		$this->footerControlFactory = $controlFactory;
	}

	public function getFooterComponent(): Control
	{
		return $this['footer'];
	}

	protected function createComponentFooter(): Control
	{
		return $this->footerControlFactory->create();
	}

	protected function attachComponentFooter(Control $component): void
	{
		$this->addComponent($component, 'footer');
	}

}
