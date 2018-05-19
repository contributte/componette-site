<?php declare(strict_types = 1);

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

	protected function createComponentSearch(): Search
	{
		$search = $this->searchFactory->create();

		$search['form']->setMethod('GET');
		$search['form']->setAction($this->link(Destination::FRONT_PORTAL_SEARCH));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(Destination::FRONT_PORTAL_SEARCH, ['q' => '_QUERY_']));

		return $search;
	}

	protected function createComponentModal(): AddonModal
	{
		return $this->addonModalFactory->create();
	}

	protected function createComponentSideMenu(): SideMenu
	{
		return $this->sideMenuFactory->create();
	}

	protected function createComponentComponetters(): Componetters
	{
		return $this->componettersFactory->create();
	}

}
