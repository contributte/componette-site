<?php

namespace App\Modules\Front\Portal\Base;

use App\Model\UI\Destination;
use App\Modules\Front\Base\BasePresenter as BaseFrontPresenter;
use App\Modules\Front\Portal\Base\Controls\Componetters\Componetters;
use App\Modules\Front\Portal\Base\Controls\Componetters\IComponettersFactory;
use App\Modules\Front\Portal\Controls\AddonModal\AddonModal;
use App\Modules\Front\Portal\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Portal\Controls\Search\ISearchFactory;
use App\Modules\Front\Portal\Controls\Search\Search;
use App\Modules\Front\Portal\Controls\SideMenu\ISideMenuFactory;
use App\Modules\Front\Portal\Controls\SideMenu\SideMenu;
use Nette\Application\UI\ComponentReflection;

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


	/**
	 * TEMPLATES ***************************************************************
	 * *************************************************************************
	 */

	/**
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		$dir = dirname($this->getReflection()->getFileName());

		return [
			$dir . '/templates/' . $this->view . '.latte',
		];
	}

	/**
	 * @return array
	 */
	public function formatLayoutTemplateFiles()
	{
		$list = [];

		$rf1 = new ComponentReflection(get_called_class());
		$dir1 = dirname($rf1->getFileName());
		$list[] = $dir1 . '/templates/@layout.latte';

		$rf2 = new ComponentReflection(self::class);
		$dir2 = dirname($rf2->getFileName());
		$list[] = $dir2 . '/templates/@layout.latte';

		return $list;
	}

}
