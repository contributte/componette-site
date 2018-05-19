<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonModal;

interface IAddonModalFactory
{

	public function create(): AddonModal;

}
