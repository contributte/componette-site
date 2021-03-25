<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

trait SvgComponent
{

	private ControlFactory $svgControlFactory;

	public function injectSvgControlFactory(ControlFactory $controlFactory): void
	{
		$this->svgControlFactory = $controlFactory;
	}

	public function getSvgComponent(): Control
	{
		return $this['svg'];
	}

	protected function createComponentSvg(): Control
	{
		return $this->svgControlFactory->create();
	}

	protected function attachComponentSvg(Control $component): void
	{
		$this->addComponent($component, 'svg');
	}

}
