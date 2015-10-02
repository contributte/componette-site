<?php

namespace App\Modules\Front\Controls\PackageDetail;

use App\Model\ORM\Package;

interface IPackageDetailFactory
{

    /**
     * @param Package $package
     * @return PackageDetail
     */
    function create(Package $package);
}