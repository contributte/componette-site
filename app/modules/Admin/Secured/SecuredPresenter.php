<?php

namespace App\Modules\Admin\Secured;

use App\Modules\Admin\Base\BasePresenter;
use Nette\Application\ForbiddenRequestException;

abstract class SecuredPresenter extends BasePresenter
{

	/**
	 * @param mixed $element
	 * @return void
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
