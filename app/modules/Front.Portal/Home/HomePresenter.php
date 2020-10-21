<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Home;

use App\Model\Database\Query\LatestActivityAddonsQuery;
use App\Model\Database\Query\LatestAddedAddonsQuery;
use App\Modules\Front\Portal\Addon\Controls\FeaturedAddon\FeaturedAddonComponent;
use App\Modules\Front\Portal\Base\BaseAddonPresenter;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\News\NewsComponent;
use App\Modules\Front\Portal\Base\Controls\ReleaseList\IReleaseListFactory;
use App\Modules\Front\Portal\Base\Controls\ReleaseList\ReleaseList;
use App\Modules\Front\Portal\Base\Controls\Search\Search;
use App\Modules\Front\Portal\Base\Controls\Tags\TagsComponent;

final class HomePresenter extends BaseAddonPresenter
{

	use NewsComponent;
	use TagsComponent;
	use FeaturedAddonComponent;

	/** @var IReleaseListFactory @inject */
	public $releaseListFactory;

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
		return $this->createAddonListControl(new LatestAddedAddonsQuery());
	}

	protected function createComponentLatestActivity(): AddonList
	{
		return $this->createAddonListControl(new LatestActivityAddonsQuery());
	}

	protected function createComponentLatestReleases(): ReleaseList
	{
		return $this->releaseListFactory->create();
	}

}
