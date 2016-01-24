<?php

namespace App\Modules\Front\Controls\AddonList;

use App\Core\UI\BaseControl;
use App\Model\ORM\Addon\Addon;
use App\Modules\Front\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Controls\AddonMeta\IAddonMetaFactory;
use Nextras\Orm\Collection\ICollection;

final class AddonList extends BaseControl
{

    /** @var ICollection|Addon[] */
    private $addons;

    /** @var IAddonMetaFactory */
    private $addonMetaFactory;

    /**
     * @param ICollection $addons
     * @param IAddonMetaFactory $addonMetaFactory
     */
    public function __construct(ICollection $addons, IAddonMetaFactory $addonMetaFactory)
    {
        parent::__construct();
        $this->addons = $addons;
        $this->addonMetaFactory = $addonMetaFactory;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return AddonMeta
     */
    protected function createComponentMeta()
    {
        return $this->addonMetaFactory->create();
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->addons = $this->addons;

        $this->template->setFile(__DIR__ . '/templates/list.latte');
        $this->template->render();
    }

}
