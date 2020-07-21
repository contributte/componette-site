<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base;

use App\Model\Database\Query\QueryObject;
use App\Model\Services\Search\Search;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\AddonList\IAddonListFactory;

abstract class BaseAddonPresenter extends BasePresenter
{

	/** @var IAddonListFactory @inject */
	public $addonListFactory;

	/** @var Search @inject */
	public $search;

	/**
	 * Common render method
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->template->search = $this->search;
	}

	/**
	 * CONTROLS ****************************************************************
	 */
	protected function createAddonListControl(QueryObject $queryObject): AddonList
	{
		return $this->addonListFactory->create($queryObject);
	}

}
