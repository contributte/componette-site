<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header\Menu;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	/**
	 * @param MenuLink[] $links
	 */
	public function render(array $links): void
	{
		$this->template->links = $links;
		$this->template->render();
	}

}
