<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Heading;

use App\Model\UI\BaseRenderControl;
use Nette\HtmlStringable;

class Control extends BaseRenderControl
{

	public function render(string|HtmlStringable $content, string $type = 'h2'): void
	{
		$this->template->setParameters(['content' => $content, 'type' => $type])->render();
	}

}
