<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Heading;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	public function render(string $content, string $type = 'h2'): void
	{
		$this->template->setParameters(['content' => $content, 'type' => $type])->render();
	}

}
