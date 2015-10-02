<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Modules\Front\Controls\PackageList\IPackageListFactory;
use App\Modules\Front\Controls\PackageList\PackageList;

final class ListPresenter extends BasePresenter
{

    /** @var IPackageListFactory @inject */
    public $packageListFactory;

    /** @var PackagesRepository @inject */
    public $packagesRepository;

    /** @var Package[] */
    private $packages;

    /**
     * DEFAULT *****************************************************************
     * *************************************************************************
     */

    public function actionDefault()
    {
        $this->packages = $this->packagesRepository
            ->findActive()
            ->limitBy(40);
    }

    /**
     * BY OTHER ****************************************************************
     */

    public function actionOwner($owner)
    {
        $this->packages = $this->packagesRepository
            ->findActive()
            ->limitBy(40);
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return PackageList
     */
    protected function createComponentPackages()
    {
        return $this->packageListFactory->create($this->packages);
    }
}
