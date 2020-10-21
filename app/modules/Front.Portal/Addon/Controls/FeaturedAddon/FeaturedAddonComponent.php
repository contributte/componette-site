<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Addon\Controls\FeaturedAddon;

trait FeaturedAddonComponent
{

	private ControlFactory $featuredAddonControlFactory;

	public function injectFeaturedAddonControlFactory(ControlFactory $controlFactory): void
	{
		$this->featuredAddonControlFactory = $controlFactory;
	}

	public function getFeaturedAddonComponent(): Control
	{
		return $this['featuredAddon'];
	}

	protected function createComponentFeaturedAddon(): Control
	{
		return $this->featuredAddonControlFactory->create();
	}

	protected function attachComponentFeaturedAddon(Control $component): void
	{
		$this->addComponent($component, 'featuredAddon');
	}

}
