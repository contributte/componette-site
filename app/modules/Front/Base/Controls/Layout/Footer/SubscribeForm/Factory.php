<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm;

use App\Model\Forms\Form;
use App\Model\Forms\InjectFormFactory;
use Nette\Forms\Container;
use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateInject=true)
 */
class Factory
{

	use InjectFormFactory;
	use SmartObject;

	public const EMAIL = 'email';

	public const SUBMIT = 'submit';

	public function create(): Form
	{
		$form = $this->formFactory->create();
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
