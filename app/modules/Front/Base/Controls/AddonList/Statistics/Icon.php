<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Statistics;

use App\Modules\Front\Base\Controls\Svg\SvgProps;
use Nette\Utils\Html;

final class Icon
{

	/** @var Control */
	private Control $control;

	public function __construct(Control $control)
	{
		$this->control = $control;
	}

	public function render(string $image, string $type): Html
	{
		return $this->control
			->getSvgComponent()
			->renderToHtml(new SvgProps(
				[
					SvgProps::CLASS_NAME => 'w-4 h-4',
					SvgProps::IMAGE => $image,
					SvgProps::SIZE => 64,
					SvgProps::FILL => '467A85',
					SvgProps::TYPE => $type,
				]
			));
	}

}
