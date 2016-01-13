<?php

namespace App\Modules\Front;

use App\Model\Facade\SearchFacade;
use App\Model\ORM\Addon\Addon;
use App\Modules\Front\Controls\AddonList\AddonList;
use Nextras\Orm\Collection\ICollection;

final class ListPresenter extends BaseAddonPresenter
{

    /** @var SearchFacade @inject */
    public $searchFacade;

    /** @var ICollection|Addon[] */
    private $addons;

    /**
     * DEFAULT *****************************************************************
     */

    public function actionDefault()
    {
        $this->addons = $this->searchFacade->findAll();
    }

    /**
     * BY OTHER ****************************************************************
     */

    /**
     * @param string $slug
     */
    public function actionOwner($slug)
    {
        $this->addons = $this->searchFacade->findByOwner($slug);
    }

    /**
     * @param string $slug
     */
    public function renderOwner($slug)
    {
        $this->template->owner = $slug;
    }

    /**
     * BY SEARCH ***************************************************************
     */

    /**
     * @param string $q
     */
    public function actionSearch($q)
    {
        $this->addons = $this->searchFacade->findByQuery($q);
    }

    public function renderSearch()
    {
        if ($this->isAjax()) {
            $this->redrawControl('search-result');
        }
    }

    /**
     * BY TAG *****************************************************************
     */

    /**
     * @param string $tag
     */
    public function actionTag($tag)
    {
        $this->addons = $this->searchFacade->findByTag($tag);
    }

    /**
     * @param string $tag
     */
    public function renderTag($tag)
    {
        $this->template->tag = $tag;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return AddonList
     */
    protected function createComponentAddons()
    {
        return $this->createAddonListControl($this->addons);
    }
}
