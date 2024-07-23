<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Statistics;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;

class Control extends BaseRenderControl
{

	use SvgComponent;

	public function render(Addon $addon, bool $featured = false, bool $inline = false): void
	{
		$this->template->setParameters([
			'addon' => $addon,
			'featured' => $featured,
			'inline' => $inline,
			'icon' => new Icon(),
			'statistics' => [
				'className' => 'flex-shrink-0 w-5 h-5 mb-3 lg:mb-4 mr-2',
				'image' => 'bar-chart-fill',
				'fill' => 'C1CCDB',
				'size' => 64,
				'type' => 'business',
			],
		]);
	}

}
