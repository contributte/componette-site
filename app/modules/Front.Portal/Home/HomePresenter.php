<?php

namespace App\Modules\Front\Portal\Home;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\LatestActivityAddonsQuery;
use App\Model\Database\Query\LatestAddedAddonsQuery;
use App\Modules\Front\Portal\Base\BaseAddonPresenter;
use App\Modules\Front\Portal\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Controls\Search\Search;
use Minetro\Nextras\Orm\QueryObject\Queryable;
use Nextras\Orm\Collection\ICollection;

final class HomePresenter extends BaseAddonPresenter
{

	/** @var AddonRepository @inject */
	public $addonRepository;

	/** @var ICollection|Addon[] */
	private $newest;

	/** @var ICollection|Addon[] */
	private $lastActive;

	/**
	 * Find addons by criteria
	 */
	public function actionDefault()
	{
		$this->newest = $this->addonRepository->fetch(new LatestAddedAddonsQuery(), Queryable::HYDRATION_ENTITY);
		$this->lastActive = $this->addonRepository->fetch(new LatestActivityAddonsQuery(), Queryable::HYDRATION_ENTITY);
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @return Search
	 */
	protected function createComponentSearch()
	{
		$search = parent::createComponentSearch();
		$search['form']['q']->controlPrototype->autofocus = TRUE;

		return $search;
	}

	/**
	 * @return AddonList
	 */
	protected function createComponentLatestAdded()
	{
		return $this->createAddonListControl($this->newest);
	}

	/**
	 * @return AddonList
	 */
	protected function createComponentLatestActivity()
	{
		return $this->createAddonListControl($this->lastActive);
	}

}
