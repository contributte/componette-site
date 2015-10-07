<?php

namespace App\Modules\Front;

use App\Model\Portal;
use App\Modules\Front\Controls\PackageModal\IPackageModalFactory;
use App\Modules\Front\Controls\Search\ISearchFactory;
use App\Modules\Front\Controls\Search\PackageModal;
use App\Modules\Front\Controls\Search\Search;
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

    /** @var IPackageModalFactory @inject */
    public $packageModalFactory;

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
        $search->onSearch[] = function ($q) {
            $this->redirect(':Front:List:search', $q);
        };

        return $search;
    }

    /**
     * @return PackageModal
     */
    protected function createComponentModal()
    {
        return $this->packageModalFactory->create();
    }

}
