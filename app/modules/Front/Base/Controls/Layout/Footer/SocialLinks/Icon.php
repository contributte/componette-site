<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use App\Modules\Front\Base\Controls\Svg\SvgProps;
use Nette\SmartObject;
use Nette\Utils\Html;

final class Icon
{

	use SmartObject;

	/** @var Control */
	private Control $control;

	public function __construct(Control $control)
	{
		$this->control = $control;
	}

	public function render(SocialLink $link): Html
	{
		return $this
			->control
			->getSvgComponent()
			->renderToHtml(
				new SvgProps(
					[
						SvgProps::ALT => $link->getName(),
						SvgProps::CLASS_NAME => 'w-6 h-6 transition duration-150 ease-in-out opacity-25 group-hover:opacity-50',
						SvgProps::FILL => '718096',
						SvgProps::IMAGE => $link->getIcon(),
						SvgProps::SIZE => 64,
					]
				)
			);
	}

}
