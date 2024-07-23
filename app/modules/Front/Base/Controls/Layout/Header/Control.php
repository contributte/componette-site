<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Header;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Header\Menu\MenuComponent;
use App\Modules\Front\Base\Controls\Layout\Header\Menu\MenuLink;
use App\Modules\Front\Base\Controls\Layout\Header\Menu\MenuProps;

class Control extends BaseRenderControl
{

	use MenuComponent;

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this
			->template
			->setParameters(
				[
					'home' => $this->presenter->link(':Front:Home:'),
					'menu' => new MenuProps([MenuProps::LINKS => $this->links()]),
				]
			);
	}

	/**
	 * @return array<MenuLink>
	 */
	private function links(): array
	{
		return [
			new MenuLink('Nette', 'https://pla.nette.org'),
			new MenuLink('Forum', 'https://forum.nette.org'),
			new MenuLink('Blog', 'https://blog.nette.org'),
			new MenuLink('Contributte', 'https://contributte.org'),
			new MenuLink('Sponsorship', 'https://contributte.org/partners.html'),
		];
	}

}
