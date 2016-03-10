<?php

namespace App\Modules\Front\Portal;

use App\Model\ORM\Addon\Addon;
use App\Modules\Front\Portal\Controls\AddonList\AddonList;
use Nextras\Orm\Collection\ICollection;

final class HomePresenter extends BaseAddonPresenter
{

    /** @var ICollection|Addon[] */
    private $newest;

    /** @var ICollection|Addon[] */
    private $lastActive;

    /**
     * Find addons by criteria
     */
    public function actionDefault()
    {
        $this->newest = $this->addonFacade->findNewest(5);
        $this->lastActive = $this->addonFacade->findByLastActivity(5);
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return AddonList
     */
    protected function createComponentNewest()
    {
        return $this->createAddonListControl($this->newest);
    }

    /**
     * @return AddonList
     */
    protected function createComponentLastActive()
    {
        return $this->createAddonListControl($this->lastActive);
    }

}
