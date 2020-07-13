<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header\Menu;

use Nette\SmartObject;

final class MenuLink
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

	public function __construct(string $name, string $url)
	{
		$this->name = $name;
		$this->url = $url;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

}
