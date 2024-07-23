<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Statistics;

final class Icon
{

	/**
	 * @return array<string, mixed>
	 */
	public function getSvgProps(string $image, string $type): array
	{
		return [
			'className' => 'w-4 h-4',
			'image' => $image,
			'size' => 64,
			'fill' => '467A85',
			'type' => $type,
		];
	}

}
