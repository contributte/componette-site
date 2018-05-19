<?php declare(strict_types = 1);

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
	 */
	public function actionIn(): void
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Home:');
		}
	}

	/**
	 * Sign user out
	 */
	public function actionOut(): void
	{
		$this->user->logout();
		$this->redirect('in');
	}

	protected function createComponentLogin(): Login
	{
		$control = $this->loginFactory->create();

		$control->onLoggedIn[] = function (): void {
			$this->redirect('Home:');
		};

		return $control;
	}

}
