<?php declare(strict_types = 1);

namespace App\Modules\Admin\Home;

use App\Modules\Admin\Secured\SecuredPresenter;

final class HomePresenter extends SecuredPresenter
{

	/**
	 * Redirect to addons
	 */
	public function actionDefault(): void
	{
		$this->redirect('Addon:');
	}

}
