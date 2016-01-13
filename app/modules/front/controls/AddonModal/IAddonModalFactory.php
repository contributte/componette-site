<?php

namespace App\Modules\Front\Controls\AddonModal;

interface IAddonModalFactory
{

    /**
     * @return AddonModal
     */
    function create();
}