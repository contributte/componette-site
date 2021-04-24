<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Statistics;

use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use App\Modules\Front\Base\Controls\Svg\SvgProps;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\PropsControl\PropsControl;
use Wavevision\PropsControl\ValidProps;

/**
 * @DIService(generateComponent=true)
 */
class Control extends PropsControl
{

	use SvgComponent;

	protected function beforeRender(ValidProps $props): void
	{
		parent::beforeRender($props);
		$this->template->setParameters([
			'icon' => new Icon($this),
			'statistics' => new SvgProps([
				SvgProps::CLASS_NAME => 'flex-shrink-0 w-5 h-5 mb-3 lg:mb-4 mr-2',
				SvgProps::IMAGE => 'bar-chart-fill',
				SvgProps::FILL => 'C1CCDB',
				SvgProps::SIZE => 64,
				SvgProps::TYPE => 'business',
			]),
		]);
	}

	protected function getPropsClass(): string
	{
		return StatisticsProps::class;
	}

}
