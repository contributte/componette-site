<?php

namespace App\Modules\Admin\Home;

use App\Modules\Admin\Secured\SecuredPresenter;

final class HomePresenter extends SecuredPresenter
{

	/**
	 * Redirect to addons
	 *
	 * @return void
	 */
	public function actionDefault()
	{
		$this->redirect('Addon:');
	}

}
