<?php

namespace App\Modules\Front\Portal;

use App\Model\Facade\AddonFacade;
use App\Model\Search\Search;
use App\Modules\Front\Portal\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Controls\AddonList\IAddonListFactory;
use Nextras\Orm\Collection\ICollection;

abstract class BaseAddonPresenter extends BasePresenter
{

	/** @var IAddonListFactory @inject */
	public $addonListFactory;

	/** @var AddonFacade @inject */
	public $addonFacade;

	/** @var Search @inject */
	public $search;

	/**
	 * Common render method
	 */
	protected function beforeRender()
	{
		parent::beforeRender();

		$this->template->search = $this->search;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @param ICollection $addons
	 * @return AddonList
	 */
	protected function createAddonListControl(ICollection $addons)
	{
		return $this->addonListFactory->create($addons);
	}

}
