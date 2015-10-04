<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use Nextras\Orm\Collection\ICollection;

final class HomePresenter extends BasePackagesPresenter
{

    /** @var ICollection|Package[] */
    private $newest;

    /** @var ICollection|Package[] */
    private $recentlyPushed;

    public function actionDefault()
    {
        $this->newest = $this->packagesFacade->findNewests();
        $this->recentlyPushed = $this->packagesFacade->findRecentlyPushed();
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return Controls\PackageList\PackageList
     */
    protected function createComponentNewest()
    {
        return $this->createPackagesControl($this->newest);
    }

    /**
     * @return Controls\PackageList\PackageList
     */
    protected function createComponentRecentlyPushed()
    {
        return $this->createPackagesControl($this->recentlyPushed);
    }
}
