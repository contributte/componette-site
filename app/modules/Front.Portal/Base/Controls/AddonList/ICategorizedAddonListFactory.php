<?php

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

interface ICategorizedAddonListFactory
{

	/**
	 * @return CategorizedAddonList
	 */
	public function create();

}
