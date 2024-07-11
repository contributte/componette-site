<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

use App\Model\UI\BasePropsControl;
use Wavevision\PropsControl\ValidProps;
use Contributte\Utils\Arrays;
use Contributte\Utils\Strings;

class Control extends BasePropsControl
{

	private const URL = 'https://obr.vercel.app/remixicon';

	protected function beforeRender(ValidProps $props): void
	{
		parent::beforeRender($props);
		$this->template->setParameters(['url' => $this->url($props)]);
	}

	protected function getPropsClass(): string
	{
		return SvgProps::class;
	}

	private function url(ValidProps $props): string
	{
		return implode(
			'/',
			array_filter([
				self::URL,
				$props->get(SvgProps::TYPE),
				$props->get(SvgProps::IMAGE),
				$props->get(SvgProps::SIZE),
				$props->get(SvgProps::FILL),
			], fn($part) => $part !== null)
		);
	}

}
