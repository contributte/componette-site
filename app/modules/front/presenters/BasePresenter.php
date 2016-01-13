<?php

namespace App\Modules\Front;

use App\Model\Portal;
use App\Modules\Front\Controls\AddonModal\AddonModal;
use App\Modules\Front\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Controls\Search\ISearchFactory;
use App\Modules\Front\Controls\Search\Search;
use App\Modules\Front\Controls\SideMenu\ISideMenuFactory;
use App\Modules\Front\Controls\SideMenu\SideMenu;
use App\Modules\Front\Controls\Statistics\IStatisticsFactory;
use App\Modules\Front\Controls\Statistics\Statistics;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

    /** @var Portal @inject */
    public $portal;

    /** @var ISearchFactory @inject */
    public $searchFactory;

    /** @var IAddonModalFactory @inject */
    public $addonModalFactory;

    /** @var ISideMenuFactory @inject */
    public $sideMenuFactory;

    /** @var IStatisticsFactory @inject */
    public $statisticsFactory;

    /**
     * Common template method
     */
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->portal = $this->portal;
        $this->template->rev = $this->portal->expand('build.rev');
        $this->template->debug = $this->portal->isDebug();
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return Search
     */
    protected function createComponentSearch()
    {
        $search = $this->searchFactory->create();

        $search['form']['q']
            ->controlPrototype
            ->data('handle', $this->link(':Front:List:search', ['q' => '_QUERY_']));

        $search->onSearch[] = function ($q) {
            $this->redirect(':Front:List:search', $q);
        };

        return $search;
    }

    /**
     * @return AddonModal
     */
    protected function createComponentModal()
    {
        return $this->addonModalFactory->create();
    }

    /**
     * @return SideMenu
     */
    protected function createComponentSideMenu()
    {
        return $this->sideMenuFactory->create();
    }

    /**
     * @return Statistics
     */
    protected function createComponentStatistics()
    {
        return $this->statisticsFactory->create();
    }

}
