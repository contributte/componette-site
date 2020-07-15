<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use App\Model\UI\BasePropsControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\PropsControl\ValidProps;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BasePropsControl
{

	use SvgComponent;

	protected function beforeRender(ValidProps $props): void
	{
		parent::beforeRender($props);
		$this->template->setParameters(['icon' => new Icon($this)]);
	}

	protected function getPropsClass(): string
	{
		return SocialLinksProps::class;
	}

}
