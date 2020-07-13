<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

use App\Model\Forms\Form;
use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Footer\Heading\HeadingComponent;
use App\Modules\Front\Base\Controls\Layout\Footer\Heading\HeadingProps;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BaseRenderControl
{

	use HeadingComponent;
	use InjectFactory;
	use InjectHandler;

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this
			->template
			->setParameters(
				[
					'heading' => new HeadingProps([HeadingProps::TEXT => 'Subscribe to our newsletter']),
				]
			);
	}

	protected function createComponentForm(): Form
	{
		$form = $this->factory->create();
		$form->onSuccess[] = [$this->handler, 'process'];
		return $form;
	}

}
