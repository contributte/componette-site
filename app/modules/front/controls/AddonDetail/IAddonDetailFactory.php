<?php

namespace App\Modules\Front\Controls\AddonDetail;

use App\Model\ORM\Addon\Addon;

interface IAddonDetailFactory
{

    /**
     * @param Addon $addon
     * @return AddonDetail
     */
    function create(Addon $addon);
}