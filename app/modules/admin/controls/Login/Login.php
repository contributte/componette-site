<?php

namespace App\Modules\Admin\Controls\Login;

use App\Core\UI\BaseControl;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

final class Login extends BaseControl
{

    /** @var array */
    public $onLoggedIn = [];

    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * @return Form
     */
    protected function createComponentForm()
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

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
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

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/login.latte');
        $this->template->render();
    }

}
