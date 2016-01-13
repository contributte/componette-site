<?php

namespace App\Modules\Front;

use App\Model\Facade\AddonFacade;
use App\Model\ORM\Addon\Addon;
use App\Modules\Front\Controls\AddonDetail\AddonDetail;
use App\Modules\Front\Controls\AddonDetail\IAddonDetailFactory;

final class AddonPresenter extends BasePresenter
{

    /** @var AddonFacade @inject */
    public $addonFacade;

    /** @var IAddonDetailFactory @inject */
    public $addonDetailFactory;

    /** @var Addon */
    protected $addon;

    /**
     * @param int $slug
     */
    public function actionDetail($slug)
    {
        $this->addon = $this->addonFacade->getById($slug);
        if (!$this->addon) {
            $this->error('Addon not found');
        }
    }

    public function renderDetail()
    {
        $this->template->addon = $this->addon;
    }

    /**
     * @return AddonDetail
     */
    protected function createComponentAddon()
    {
        return $this->addonDetailFactory->create($this->addon);
    }

}
