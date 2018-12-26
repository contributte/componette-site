<?php declare(strict_types = 1);

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
use Contributte\Nextras\Orm\QueryObject\Queryable;
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

	public function actionAll(): void
	{
	}

	/**
	 * AUTHOR ******************************************************************
	 */

	public function actionAuthor(string $slug): void
	{
		$query = new SearchAddonsQuery();
		$query->byAuthor($slug);

		$this->addons = $this->addonRepository->fetchEntities($query);
	}

	public function renderAuthor(string $slug): void
	{
		$this->template->author = $slug;
	}

	/**
	 * BY SEARCH ***************************************************************
	 */

	public function actionSearch(?string $q): void
	{
		if (!$q) {
			$this->redirect(Destination::FRONT_PORTAL_HOMEPAGE);
		}

		if (strlen($q) > 100) {
			$this->redirect('this', ['q' => substr($q, 0, 100)]);
		}

		$query = new SearchAddonsQuery();
		$query->byQuery($q);

		$this->addons = $this->addonRepository->fetchEntities($query);
	}

	public function renderSearch(): void
	{
		if ($this->isAjax()) {
			$this->redrawControl('search-result');
		}

		$this->template->addons = $this->addons;
	}

	/**
	 * BY TAG *****************************************************************
	 */

	public function actionTag(string $tag): void
	{
		$query = new SearchAddonsQuery();
		$query->byTag($tag);

		$this->addons = $this->addonRepository->fetch($query, Queryable::HYDRATION_ENTITY);
	}

	public function renderTag(string $tag): void
	{
		$this->template->tag = $tag;
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

	protected function createComponentAddons(): AddonList
	{
		return $this->createAddonListControl($this->addons);
	}

	protected function createComponentCategorizedAddons(): CategorizedAddonList
	{
		return $this->categorizedAddonsListFactory->create();
	}

}
