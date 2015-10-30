<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use Nextras\Orm\Collection\ICollection;

final class HomePresenter extends BasePackagesPresenter
{

    /** @var ICollection|Package[] */
    private $newest;

    /** @var ICollection|Package[] */
    private $lastActive;

    /** @var ICollection|Package[] */
    private $mostPopular;


    public function actionDefault()
    {
        $this->newest = $this->packagesFacade->findNewests();
        $this->lastActive = $this->packagesFacade->findByLastActivity();
        $this->mostPopular = $this->packagesFacade->findMostPopular();
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
    protected function createComponentLastActive()
    {
        return $this->createPackagesControl($this->lastActive);
    }


    /**
     * @return Controls\PackageList\PackageList
     */
    protected function createComponentMostPopular()
    {
        return $this->createPackagesControl($this->mostPopular);
    }


}
