<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Avatar;

trait AvatarComponent
{

	private ControlFactory $avatarControlFactory;

	public function injectAvatarControlFactory(ControlFactory $controlFactory): void
	{
		$this->avatarControlFactory = $controlFactory;
	}

	public function getAvatarComponent(): Control
	{
		return $this['avatar'];
	}

	protected function createComponentAvatar(): Control
	{
		return $this->avatarControlFactory->create();
	}

	protected function attachComponentAvatar(Control $component): void
	{
		$this->addComponent($component, 'avatar');
	}

}
