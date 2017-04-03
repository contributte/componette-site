<?php

namespace App\Modules\Front\Portal\Base;

use App\Model\UI\Destination;
use App\Modules\Front\Base\BasePresenter as BaseFrontPresenter;
use App\Modules\Front\Portal\Base\Controls\AddonModal\AddonModal;
use App\Modules\Front\Portal\Base\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Portal\Base\Controls\Componetters\Componetters;
use App\Modules\Front\Portal\Base\Controls\Componetters\IComponettersFactory;
use App\Modules\Front\Portal\Base\Controls\Search\ISearchFactory;
use App\Modules\Front\Portal\Base\Controls\Search\Search;
use App\Modules\Front\Portal\Base\Controls\SideMenu\ISideMenuFactory;
use App\Modules\Front\Portal\Base\Controls\SideMenu\SideMenu;

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

	/** @var IComponettersFactory @inject */
	public $componettersFactory;

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
		$search['form']->setAction($this->link(Destination::FRONT_PORTAL_SEARCH));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(Destination::FRONT_PORTAL_SEARCH, ['q' => '_QUERY_']));

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
	 * @return Componetters
	 */
	protected function createComponentComponetters()
	{
		return $this->componettersFactory->create();
	}

}
