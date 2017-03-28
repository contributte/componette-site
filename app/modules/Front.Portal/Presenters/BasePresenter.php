<?php

namespace App\Modules\Front\Portal;

use App\Model\Portal;
use App\Modules\Front\BasePresenter as BaseFrontPresenter;
use App\Modules\Front\Portal\Controls\AddonModal\AddonModal;
use App\Modules\Front\Portal\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Portal\Controls\Search\ISearchFactory;
use App\Modules\Front\Portal\Controls\Search\Search;
use App\Modules\Front\Portal\Controls\SideMenu\ISideMenuFactory;
use App\Modules\Front\Portal\Controls\SideMenu\SideMenu;
use App\Modules\Front\Portal\Controls\Statistics\IStatisticsFactory;
use App\Modules\Front\Portal\Controls\Statistics\Statistics;

/**
 * Base presenter for all portal presenters.
 */
abstract class BasePresenter extends BaseFrontPresenter
{

	/** @var ISearchFactory @inject */
	public $searchFactory;

	/** @var IAddonModalFactory @inject */
	public $addonModalFactory;

	/** @var ISideMenuFactory @inject */
	public $sideMenuFactory;

	/** @var IStatisticsFactory @inject */
	public $statisticsFactory;

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @return Search
	 */
	protected function createComponentSearch()
	{
		$search = $this->searchFactory->create();

		$search['form']->setMethod('GET');
		$search['form']->setAction($this->link(':Front:Portal:List:search'));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(':Front:Portal:List:search', ['q' => '_QUERY_']));

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
