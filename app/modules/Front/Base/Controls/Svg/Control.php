<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

use App\Model\UI\BasePropsControl;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\PropsControl\ValidProps;
use Wavevision\Utils\Arrays;
use Wavevision\Utils\Path;

/**
 * @DIService(generateComponent=true)
 */
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
		return Path::join(
			self::URL,
			...Arrays::map(
				[SvgProps::TYPE, SvgProps::IMAGE, SvgProps::SIZE, SvgProps::FILL],
				function (string $prop) use ($props): ?string {
					$value = $props->get($prop);
					return $value ? (string)$value : null;
				}
			)
		);
	}

}
