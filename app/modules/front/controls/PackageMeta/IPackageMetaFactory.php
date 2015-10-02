<?php

namespace App\Modules\Front\Controls\PackageMeta;

interface IPackageMetaFactory
{

    /**
     * @return PackageMeta
     */
    function create();
}