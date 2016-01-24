<?php

namespace App\Modules\Front\Controls\AddonDetail;

use App\Model\ORM\Addon\Addon;

interface IAddonDetailFactory
{

    /**
     * @param Addon $addon
     * @return AddonDetail
     */
    public function create(Addon $addon);

}
