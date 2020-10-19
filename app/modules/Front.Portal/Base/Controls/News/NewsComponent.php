<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\News;

trait NewsComponent
{

	private ControlFactory $newsControlFactory;

	public function injectNewsControlFactory(ControlFactory $controlFactory): void
	{
		$this->newsControlFactory = $controlFactory;
	}

	public function getNewsComponent(): Control
	{
		return $this['news'];
	}

	protected function createComponentNews(): Control
	{
		return $this->newsControlFactory->create();
	}

	protected function attachComponentNews(Control $component): void
	{
		$this->addComponent($component, 'news');
	}

}
