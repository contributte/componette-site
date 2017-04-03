<?php

namespace App\Modules\Admin\Sign;

use App\Modules\Admin\Base\BasePresenter;
use App\Modules\Admin\Sign\Controls\Login\ILoginFactory;
use App\Modules\Admin\Sign\Controls\Login\Login;

final class SignPresenter extends BasePresenter
{

	/** @var ILoginFactory @inject */
	public $loginFactory;

	/**
	 * Sign user in
	 *
	 * @return void
	 */
	public function actionIn()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Home:');
		}
	}

	/**
	 * Sign user out
	 *
	 * @return void
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
