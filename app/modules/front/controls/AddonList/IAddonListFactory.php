<?php

namespace App\Modules\Front\Controls\AddonList;

use Nextras\Orm\Collection\ICollection;

interface IAddonListFactory
{

    /**
     * @param ICollection $addons
     * @return AddonList
     */
    function create(ICollection $addons);
}