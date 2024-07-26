<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Box;

use App\Model\UI\BaseRenderControl;
use Nette\Utils\Html;

class Control extends BaseRenderControl
{

	/**
	 * @param Html $content
	 * @param string[] $attributes
	 * @param string[] $classNames
	 */
	public function render(Html $content, array $attributes = [], array $classNames = []): void
	{
		$this->template->content = $content;
		$this->template->attributes = $attributes;
		$this->template->classNames = $classNames;
		$this->template->render();
	}

}
