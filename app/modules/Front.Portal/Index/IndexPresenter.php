<?php

namespace App\Modules\Front\Portal\Index;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\SearchAddonsQuery;
use App\Model\UI\Destination;
use App\Modules\Front\Portal\Base\BaseAddonPresenter;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\AddonList\CategorizedAddonList;
use App\Modules\Front\Portal\Base\Controls\AddonList\ICategorizedAddonListFactory;
use App\Modules\Front\Portal\Base\Controls\Search\Search;
use Minetro\Nextras\Orm\QueryObject\Queryable;
use Nextras\Orm\Collection\ICollection;

final class IndexPresenter extends BaseAddonPresenter
{

	/** @var AddonRepository @inject */
	public $addonRepository;

	/** @var ICategorizedAddonListFactory @inject */
	public $categorizedAddonsListFactory;

	/** @var ICollection|Addon[] */
	private $addons;

	/**
	 * ALL *********************************************************************
	 */

	/**
	 * @return void
	 */
	public function actionAll()
	{
	}

	/**
	 * BY OTHER ****************************************************************
	 */

	/**
	 * @param string $slug
	 * @return void
	 */
	public function actionAuthor($slug)
	{
		$query = new SearchAddonsQuery();
		$query->byAuthor($slug);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	/**
	 * @param string $slug
	 * @return void
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
	 * @return void
	 */
	public function actionSearch($q)
	{
		if (strlen($q) > 100) {
			$this->redirect('this', ['q' => substr($q, 0, 100)]);
		}

		if (empty($q)) {
			$this->redirect(Destination::FRONT_PORTAL_HOMEPAGE);
		}

		$query = new SearchAddonsQuery();
		$query->byQuery($q);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	/**
	 * @return void
	 */
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
	 * @return void
	 */
	public function actionTag($tag)
	{
		$query = new SearchAddonsQuery();
		$query->byTag($tag);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	/**
	 * @param string $tag
	 * @return void
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
		return $this->categorizedAddonsListFactory->create();
	}

}
