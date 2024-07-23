<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\News;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;

class Control extends BaseRenderControl
{

	use InjectLoadFromRss;
	use SvgComponent;

	public function render(): void
	{
		$this->template->setParameters(
			[
				'article' => $this->loadFromRss->process(),
				'icon' => [
					'className' => 'w-4 h-4 mr-2',
					'fill' => 'ffffff',
					'image' => 'blaze-line',
					'size' => 64,
					'type' => 'weather',
				],
			]
		);
	}

}
