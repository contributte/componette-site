<?php

namespace App\Modules\Front\Controls\AddonMeta;

interface IAddonMetaFactory
{

    /**
     * @return AddonMeta
     */
    function create();
}