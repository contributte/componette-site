<?php declare(strict_types = 1);

namespace App\Modules\Admin\Sign\Controls\Login;

use App\Model\UI\BaseControl;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

final class Login extends BaseControl
{

	/** @var callable[] */
	public $onLoggedIn = [];

	/** @var User */
	private $user;

	public function __construct(User $user)
	{
		parent::__construct();
		$this->user = $user;
	}

	protected function createComponentForm(): Form
	{
		$form = new Form();

		$form->addText('username', 'Username')
			->setRequired('Username is required!');

		$form->addPassword('password', 'Password')
			->setRequired('Password is required!');

		$form->addSubmit('login', 'Log in');

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}

	public function processForm(Form $form): void
	{
		$values = $form->getValues();

		try {
			// Try authenticate user
			$this->user->login(
				$values->username,
				$values->password
			);

			// Fire events
			$this->onLoggedIn();
		} catch (AuthenticationException $e) {
			$this->flashMessage($e->getMessage(), 'danger');
		}
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 */
	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/login.latte');
		$this->template->render();
	}

}
