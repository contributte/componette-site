<?php

namespace App\Modules\Front\Portal\Controls\AddonList;

use Nextras\Orm\Collection\ICollection;

interface IAddonListFactory
{

	/**
	 * @param ICollection $addons
	 * @return AddonList
	 */
	public function create($addons);

}
