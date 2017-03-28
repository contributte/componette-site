<?php

namespace App\Modules\Admin;

final class HomePresenter extends SecuredPresenter
{

	/**
	 * Redirect to addons
	 */
	public function actionDefault()
	{
		$this->redirect('Addon:');
	}

}
