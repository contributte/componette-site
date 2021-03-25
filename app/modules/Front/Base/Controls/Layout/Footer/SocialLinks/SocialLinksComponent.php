<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

trait SocialLinksComponent
{

	private ControlFactory $socialLinksControlFactory;

	public function injectSocialLinksControlFactory(ControlFactory $controlFactory): void
	{
		$this->socialLinksControlFactory = $controlFactory;
	}

	public function getSocialLinksComponent(): Control
	{
		return $this['socialLinks'];
	}

	protected function createComponentSocialLinks(): Control
	{
		return $this->socialLinksControlFactory->create();
	}

	protected function attachComponentSocialLinks(Control $component): void
	{
		$this->addComponent($component, 'socialLinks');
	}

}
