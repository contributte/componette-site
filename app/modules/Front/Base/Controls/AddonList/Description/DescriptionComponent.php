<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Description;

trait DescriptionComponent
{

	private ControlFactory $descriptionControlFactory;

	public function injectDescriptionControlFactory(ControlFactory $controlFactory): void
	{
		$this->descriptionControlFactory = $controlFactory;
	}

	public function getDescriptionComponent(): Control
	{
		return $this['description'];
	}

	protected function createComponentDescription(): Control
	{
		return $this->descriptionControlFactory->create();
	}

	protected function attachComponentDescription(Control $component): void
	{
		$this->addComponent($component, 'description');
	}

}
