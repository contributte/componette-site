<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;

class Control extends BaseRenderControl
{

	use SvgComponent;

	/**
	 * @param SocialLink[] $links
	 * @return void
	 */
	public function render(array $links): void
	{
		$this->template->setParameters([
			'links' => $links,
			'icon' => new Icon(),
		])->render();
	}

}
