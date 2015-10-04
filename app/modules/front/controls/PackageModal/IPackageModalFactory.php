<?php

namespace App\Modules\Front\Controls\PackageModal;

interface IPackageModalFactory
{

    /**
     * @return PackageModal
     */
    function create();
}