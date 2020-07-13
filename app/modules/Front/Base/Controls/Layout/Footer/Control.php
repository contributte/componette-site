<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm\SubscribeFormComponent;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BaseRenderControl
{

	use SubscribeFormComponent;

}
