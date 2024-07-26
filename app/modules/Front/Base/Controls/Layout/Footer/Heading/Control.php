<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\Heading;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	public function render(string $text): void
	{
		$this->template->setParameters(['text' => $text])->render();
	}

}
