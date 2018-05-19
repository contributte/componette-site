<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Home;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\LatestActivityAddonsQuery;
use App\Model\Database\Query\LatestAddedAddonsQuery;
use App\Modules\Front\Portal\Base\BaseAddonPresenter;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\Search\Search;
use Contributte\Nextras\Orm\QueryObject\Queryable;
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
	public function actionDefault(): void
	{
		$this->newest = $this->addonRepository->fetch(new LatestAddedAddonsQuery(), Queryable::HYDRATION_ENTITY);
		$this->lastActive = $this->addonRepository->fetch(new LatestActivityAddonsQuery(), Queryable::HYDRATION_ENTITY);
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	protected function createComponentSearch(): Search
	{
		$search = parent::createComponentSearch();
		$search['form']['q']->controlPrototype->autofocus = true;

		return $search;
	}

	protected function createComponentLatestAdded(): AddonList
	{
		return $this->createAddonListControl($this->newest);
	}

	protected function createComponentLatestActivity(): AddonList
	{
		return $this->createAddonListControl($this->lastActive);
	}

}
