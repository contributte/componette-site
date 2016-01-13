<?php

namespace App\Modules\Front;

use App\Model\ORM\Addon\Addon;
use Nextras\Orm\Collection\ICollection;

final class HomePresenter extends BaseAddonPresenter
{

    /** @var ICollection|Addon[] */
    private $newest;

    /** @var ICollection|Addon[] */
    private $lastActive;

    /** @var ICollection|Addon[] */
    private $mostPopular;

    /**
     * Find addons by criteria
     */
    public function actionDefault()
    {
        $this->newest = $this->addonFacade->findNewest();
        $this->lastActive = $this->addonFacade->findByLastActivity();
        $this->mostPopular = $this->addonFacade->findMostPopular();
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return Controls\AddonList\AddonList
     */
    protected function createComponentNewest()
    {
        return $this->createAddonListControl($this->newest);
    }

    /**
     * @return Controls\AddonList\AddonList
     */
    protected function createComponentLastActive()
    {
        return $this->createAddonListControl($this->lastActive);
    }

    /**
     * @return Controls\AddonList\AddonList
     */
    protected function createComponentMostPopular()
    {
        return $this->createAddonListControl($this->mostPopular);
    }

}
