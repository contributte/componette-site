<?php

namespace App\Modules\Admin;

use Nette\Application\ForbiddenRequestException;

abstract class SecuredPresenter extends BasePresenter
{

    /**
     * @param mixed $element
     * @throws ForbiddenRequestException
     */
    public function checkRequirements($element)
    {
        parent::checkRequirements($element);

        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

}
