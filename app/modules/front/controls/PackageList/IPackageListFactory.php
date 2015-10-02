<?php

namespace App\Modules\Front\Controls\PackageList;

use Nextras\Orm\Collection\ICollection;

interface IPackageListFactory
{

    /**
     * @param ICollection $packages
     * @return PackageList
     */
    function create(ICollection $packages);
}