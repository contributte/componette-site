<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

use App\Model\Forms\BaseForm;
use App\Model\Forms\InjectFormFactory;
use Nette\Forms\Container;
use Nette\SmartObject;

class Factory
{

	use InjectFormFactory;
	use SmartObject;

	public const EMAIL = 'email';

	public const SUBMIT = 'submit';

	public function create(): BaseForm
	{
		$form = $this->formFactory->create();
		$form->setAction('https://f3l1x.us4.list-manage.com/subscribe/post?u=7207f2543ed43156f473e11f0&id=0185f5988c');
		$form->setHtmlAttribute('target', '_blank');
		$this->email($form);
		$form->addSubmit(self::SUBMIT, 'Submit');
		return $form;
	}

	private function email(Container $container): void
	{
		$email = $container
			->addEmail(self::EMAIL)
			->setRequired();
		$email
			->getControlPrototype()
			->addAttributes(
				[
					'aria-label' => 'Email address',
					'placeholder' => 'Your e-mail',
				]
			);
	}

}
