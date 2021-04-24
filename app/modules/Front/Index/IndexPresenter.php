<?php declare(strict_types = 1);

namespace App\Modules\Front\Index;

use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\QueryObject;
use App\Model\Database\Query\SearchAddonsQuery;
use App\Model\UI\Destination;
use App\Modules\Front\Base\BaseAddonPresenter;
use App\Modules\Front\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Base\Controls\AddonList\CategorizedAddonList;
use App\Modules\Front\Base\Controls\AddonList\ICategorizedAddonListFactory;
use App\Modules\Front\Base\Controls\Layout\Heading\HeadingComponent;
use App\Modules\Front\Base\Controls\Search\Search;

final class IndexPresenter extends BaseAddonPresenter
{

	use HeadingComponent;

	/** @var AddonRepository @inject */
	public $addonRepository;

	/** @var ICategorizedAddonListFactory @inject */
	public $categorizedAddonsListFactory;

	/** @var QueryObject */
	private QueryObject $queryObject;

	public function actionAll(): void
	{
	}

	public function actionAuthor(string $slug): void
	{
		$query = new SearchAddonsQuery();
		$query->byAuthor($slug);
		$this->queryObject = $query;
	}

	public function renderAuthor(string $slug): void
	{
		$this->template->author = $slug;
	}

	public function actionSearch(?string $q): void
	{
		if (!$q) {
			$this->redirect(Destination::FRONT_HOMEPAGE);
		}

		if (strlen($q) > 100) {
			$this->redirect('this', ['q' => substr($q, 0, 100)]);
		}

		$query = new SearchAddonsQuery();
		$query->byQuery($q);
		$this->queryObject = $query;
	}

	public function renderSearch(): void
	{
		if ($this->isAjax()) {
			$this->redrawControl('search-result');
		}
	}

	public function actionTag(string $tag): void
	{
		$query = new SearchAddonsQuery();
		$query->byTag($tag);
		$this->queryObject = $query;
	}

	public function renderTag(string $tag): void
	{
		$this->template->tag = $tag;
	}

	protected function createComponentSearch(): Search
	{
		$search = parent::createComponentSearch();
		$search['form']['q']->controlPrototype->autofocus = true;

		return $search;
	}

	protected function createComponentAddons(): AddonList
	{
		return $this->createAddonListControl($this->queryObject);
	}

	protected function createComponentCategorizedAddons(): CategorizedAddonList
	{
		return $this->categorizedAddonsListFactory->create();
	}

}
