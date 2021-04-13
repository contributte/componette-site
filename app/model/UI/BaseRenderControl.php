<?php declare (strict_types = 1);

namespace App\Model\UI;

use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Wavevision\PropsControl\BaseControl;

/**
 * @property-read DefaultTemplate $template
 */
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
