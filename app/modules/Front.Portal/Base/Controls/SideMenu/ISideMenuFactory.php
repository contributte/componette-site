<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\SideMenu;

interface ISideMenuFactory
{

	public function create(): SideMenu;

}
