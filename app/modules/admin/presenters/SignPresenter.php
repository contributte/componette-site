<?php

namespace App\Modules\Admin;

use App\Modules\Admin\Controls\Login\ILoginFactory;
use App\Modules\Admin\Controls\Login\Login;

final class SignPresenter extends BasePresenter
{

    /** @var ILoginFactory @inject */
    public $loginFactory;

    /**
     * Sign user in
     */
    public function actionIn()
    {
        if ($this->user->isLoggedIn()) {
            $this->redirect('Home:');
        }
    }

    /**
     * Sign user out
     */
    public function actionOut()
    {
        $this->user->logout();
        $this->redirect('in');
    }

    /**
     * @return Login
     */
    protected function createComponentLogin()
    {
        $control = $this->loginFactory->create();

        $control->onLoggedIn[] = function () {
            $this->redirect('Home:');
        };

        return $control;
    }

}
