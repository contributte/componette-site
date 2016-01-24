<?php

namespace App\Modules\Front\Controls\AddonList;

use Nextras\Orm\Collection\ICollection;

interface IAddonListFactory
{

    /**
     * @param ICollection $addons
     * @return AddonList
     */
    public function create(ICollection $addons);

}
