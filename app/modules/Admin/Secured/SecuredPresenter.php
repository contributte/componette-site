<?php declare(strict_types = 1);

namespace App\Modules\Admin\Secured;

use App\Modules\Admin\Base\BasePresenter;
use Nette\Application\ForbiddenRequestException;

abstract class SecuredPresenter extends BasePresenter
{

	/**
	 * @param mixed $element
	 * @throws ForbiddenRequestException
	 */
	public function checkRequirements($element): void
	{
		parent::checkRequirements($element);

		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}

}
