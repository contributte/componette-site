<?php declare(strict_types = 1);

namespace App\Modules\Front\Home;

use App\Model\UI\Destination;
use App\Modules\Front\Base\BasePresenter;

final class HomePresenter extends BasePresenter
{

	/**
	 * Redirect to Portal
	 */
	public function actionDefault(): void
	{
		$this->redirect(Destination::FRONT_PORTAL_HOMEPAGE);
	}

}
