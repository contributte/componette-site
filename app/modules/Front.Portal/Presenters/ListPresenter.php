<?php

namespace App\Modules\Front\Portal;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Facade\SearchFacade;
use App\Modules\Front\Portal\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Controls\AddonList\CategorizedAddonList;
use App\Modules\Front\Portal\Controls\AddonList\ICategorizedAddonListFactory;
use App\Modules\Front\Portal\Controls\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class ListPresenter extends BaseAddonPresenter
{

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
	public function actionOwner($slug)
	{
		$this->addons = $this->searchFacade->findByOwner($slug);
	}

	/**
	 * @param string $slug
	 */
	public function renderOwner($slug)
	{
		$this->template->owner = $slug;
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

		$this->addons = $this->searchFacade->findByQuery($q);
	}

	public function renderSearch()
	{
		if ($this->isAjax()) {
			$this->redrawControl('search-result');
		}
	}

	/**
	 * BY TAG *****************************************************************
	 */

	/**
	 * @param string $tag
	 */
	public function actionTag($tag)
	{
		$this->addons = $this->searchFacade->findByTag($tag);
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
