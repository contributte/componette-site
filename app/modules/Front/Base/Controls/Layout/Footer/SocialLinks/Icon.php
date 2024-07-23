<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use App\Modules\Front\Base\Controls\Svg\SvgProps;
use Nette\SmartObject;

final class Icon
{

	use SmartObject;

	/**
	 * @return array<string, mixed>
	 */
	public function getSvgProps(SocialLink $link): array
	{
		return [
			'alt' => $link->getName(),
			'className' => 'w-6 h-6 transition duration-150 ease-in-out opacity-25 group-hover:opacity-50',
			'fill' => '718096',
			'image' => $link->getIcon(),
			'size' => 64,
		];
	}

}
