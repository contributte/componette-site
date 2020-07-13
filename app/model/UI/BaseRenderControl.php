<?php declare (strict_types = 1);

namespace App\Model\UI;

use Wavevision\PropsControl\BaseControl;

abstract class BaseRenderControl extends BaseControl
{

	public function render(): void
	{
		$this->beforeRender();
		$this->template->render();
	}

	protected function beforeRender(): void
	{
	}

}
