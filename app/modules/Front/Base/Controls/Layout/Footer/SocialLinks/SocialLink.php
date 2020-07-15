<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks;

use Nette\SmartObject;

final class SocialLink
{

	use SmartObject;

	/**
	 * @var string
	 */
	private string $name;

	/**
	 * @var string
	 */
	private string $url;

	/**
	 * @var string
	 */
	private string $icon;

	public function __construct(string $name, string $url, string $icon)
	{
		$this->name = $name;
		$this->url = $url;
		$this->icon = $icon;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getIcon(): string
	{
		return $this->icon;
	}

}
