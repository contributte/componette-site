<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Tags;

trait TagsComponent
{

	private ControlFactory $tagsControlFactory;

	public function injectTagsControlFactory(ControlFactory $controlFactory): void
	{
		$this->tagsControlFactory = $controlFactory;
	}

	public function getTagsComponent(): Control
	{
		return $this['tags'];
	}

	protected function createComponentTags(): Control
	{
		return $this->tagsControlFactory->create();
	}

	protected function attachComponentTags(Control $component): void
	{
		$this->addComponent($component, 'tags');
	}

}
