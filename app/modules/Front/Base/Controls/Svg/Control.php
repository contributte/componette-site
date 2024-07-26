<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Svg;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	private const URL = 'https://obr.vercel.app/remixicon';

	/**
	 * @param array<string, mixed> $props
	 */
	public function render(array $props): void
	{
		$this->template->setParameters(['props' => $props, 'url' => $this->url($props)])->render();
	}

	/**
	 * @param array<string, mixed> $props
	 */
	private function url(array $props): string
	{
		return implode(
			'/',
			array_filter([
				self::URL,
				$props['type'] ?? null,
				$props['image'] ?? null,
				$props['size'] ?? null,
				$props['fill'] ?? null,
			], fn($part) => $part !== null)
		);
	}

}
