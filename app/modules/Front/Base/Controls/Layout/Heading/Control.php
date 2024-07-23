<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Heading;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	/**
	 * @param array<string, mixed> $props
	 */
	public function render(array $props): void
	{
		$this->template->setParameters(['props' => $props])->render();
	}

}
