<?php

namespace App\Modules\Front\Portal\Base;

use App\Model\Services\Search\Search;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\AddonList\IAddonListFactory;
use Nextras\Orm\Collection\ICollection;

abstract class BaseAddonPresenter extends BasePresenter
{

	/** @var IAddonListFactory @inject */
	public $addonListFactory;

	/** @var Search @inject */
	public $search;

	/**
	 * Common render method
	 *
	 * @return void
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
