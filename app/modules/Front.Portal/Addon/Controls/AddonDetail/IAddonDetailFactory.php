<?php

namespace App\Modules\Front\Portal\Addon\Controls\AddonDetail;

use App\Model\Database\ORM\Addon\Addon;

interface IAddonDetailFactory
{

	/**
	 * @param Addon $addon
	 * @return AddonDetail
	 */
	public function create(Addon $addon);

}
