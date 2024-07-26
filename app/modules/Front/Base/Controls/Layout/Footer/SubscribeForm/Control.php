<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

use App\Model\Forms\BaseForm;
use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Footer\Heading\HeadingComponent;
use Nette\Forms\Form;

class Control extends BaseRenderControl
{

	use HeadingComponent;
	use InjectFactory;
	use InjectHandler;

	public function render(): void
	{
		$this
			->template
			->setParameters(
				[
					'heading' =>'Subscribe to our newsletter',
				]
			)->render();
	}

	protected function createComponentForm(): BaseForm
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function (Form $form): void {
			$this->handler->process($form);
			$this->presenter->redirect('this');
		};
		return $form;
	}

}
