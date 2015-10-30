<?php

namespace App\Modules\Front;

use App\Model\ORM\PackagesFacade;
use App\Model\Search\Search;
use App\Modules\Front\Controls\PackageList\IPackageListFactory;
use App\Modules\Front\Controls\PackageList\PackageList;
use Nextras\Orm\Collection\ICollection;

abstract class BasePackagesPresenter extends BasePresenter
{

    /** @var IPackageListFactory @inject */
    public $packageListFactory;

    /** @var PackagesFacade @inject */
    public $packagesFacade;

    /** @var Search @inject */
    public $search;

    /**
     * Common render method
     */
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->search = $this->search;
        $this->template->showSideMenu = TRUE;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @param ICollection $packages
     * @return PackageList
     */
    protected function createPackagesControl(ICollection $packages)
    {
        return $this->packageListFactory->create($packages);
    }
}
