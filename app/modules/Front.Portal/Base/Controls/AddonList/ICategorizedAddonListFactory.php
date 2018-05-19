<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

interface ICategorizedAddonListFactory
{

	public function create(): CategorizedAddonList;

}
