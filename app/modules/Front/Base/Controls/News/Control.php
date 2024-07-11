<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\News;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use App\Modules\Front\Base\Controls\Svg\SvgProps;

class Control extends BaseRenderControl
{

	use InjectLoadFromRss;
	use SvgComponent;

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->setParameters(
			[
				'article' => $this->loadFromRss->process(),
				'icon' => new SvgProps(
					[
						SvgProps::CLASS_NAME => 'w-4 h-4 mr-2',
						SvgProps::FILL => 'ffffff',
						SvgProps::IMAGE => 'blaze-line',
						SvgProps::SIZE => 64,
						SvgProps::TYPE => 'weather',
					]
				),
			]
		);
	}

}
