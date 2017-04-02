<?php

namespace App\Modules\Front\Home;

use App\Model\UI\Destination;
use App\Modules\Front\Base\BasePresenter;

final class HomePresenter extends BasePresenter
{

	/**
	 * Redirect to Portal
	 *
	 * @return void
	 */
	public function actionDefault()
	{
		$this->redirect(Destination::FRONT_PORTAL_HOMEPAGE);
	}

}
