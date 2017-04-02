<?php

namespace App\Modules\Front\Portal\Index;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\Query\SearchAddonsQuery;
use App\Model\Facade\SearchFacade;
use App\Modules\Front\Portal\Base\BaseAddonPresenter;
use App\Modules\Front\Portal\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Controls\AddonList\CategorizedAddonList;
use App\Modules\Front\Portal\Controls\AddonList\ICategorizedAddonListFactory;
use App\Modules\Front\Portal\Controls\Search\Search;
use Minetro\Nextras\Orm\QueryObject\Queryable;
use Nextras\Orm\Collection\ICollection;

final class IndexPresenter extends BaseAddonPresenter
{

	/** @var AddonRepository @inject */
	public $addonRepository;

	/** @var SearchFacade @inject */
	public $searchFacade;

	/** @var ICategorizedAddonListFactory @inject */
	public $categorizedAddonsListFactory;

	/** @var ICollection|Addon[] */
	private $addons;

	/** @var ICollection|Tag[] */
	private $categories;

	/**
	 * DEFAULT *****************************************************************
	 */

	public function actionDefault()
	{
		$this->addons = $this->searchFacade->findAll();
		$this->categories = $this->searchFacade->findCategories();
	}

	/**
	 * SORTED ******************************************************************
	 */

	/**
	 * @param string $by
	 */
	public function actionSorted($by)
	{
		$this->addons = $this->searchFacade->findSorted($by);
	}

	/**
	 * BY OTHER ****************************************************************
	 */

	/**
	 * @param string $slug
	 */
	public function actionAuthor($slug)
	{
		$query = new SearchAddonsQuery();
		$query->byAuthor($slug);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	/**
	 * @param string $slug
	 */
	public function renderAuthor($slug)
	{
		$this->template->author = $slug;
	}

	/**
	 * BY SEARCH ***************************************************************
	 */

	/**
	 * @param string $q
	 */
	public function actionSearch($q)
	{
		if (strlen($q) > 100) {
			$this->redirect('this', ['q' => substr($q, 0, 100)]);
		}

		$query = new SearchAddonsQuery();
		$query->byQuery($q);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	public function renderSearch()
	{
		if ($this->isAjax()) {
			$this->redrawControl('search-result');
		}

		$this->template->addons = $this->addons;
	}

	/**
	 * BY TAG *****************************************************************
	 */

	/**
	 * @param string $tag
	 */
	public function actionTag($tag)
	{
		$query = new SearchAddonsQuery();
		$query->byTag($tag);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	/**
	 * @param string $tag
	 */
	public function renderTag($tag)
	{
		$this->template->tag = $tag;
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
	protected function createComponentAddons()
	{
		return $this->createAddonListControl($this->addons);
	}

	/**
	 * @return CategorizedAddonList
	 */
	protected function createComponentCategorizedAddons()
	{
		return $this->categorizedAddonsListFactory->create($this->addons, $this->categories);
	}

}
