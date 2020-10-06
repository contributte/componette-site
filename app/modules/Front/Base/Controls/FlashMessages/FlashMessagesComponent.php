<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\FlashMessages;

trait FlashMessagesComponent
{

	private ControlFactory $flashMessagesControlFactory;

	public function injectFlashMessagesControlFactory(ControlFactory $controlFactory): void
	{
		$this->flashMessagesControlFactory = $controlFactory;
	}

	public function getFlashMessagesComponent(): Control
	{
		return $this['flashMessages'];
	}

	protected function createComponentFlashMessages(): Control
	{
		return $this->flashMessagesControlFactory->create();
	}

	protected function attachComponentFlashMessages(Control $component): void
	{
		$this->addComponent($component, 'flashMessages');
	}

}
