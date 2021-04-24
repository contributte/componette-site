<?php declare(strict_types = 1);

namespace App\Modules\Front\Base;

use App\Model\Database\Query\QueryObject;
use App\Model\Services\Search\Search;
use App\Model\UI\Destination;
use App\Modules\Front\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Base\Controls\AddonList\IAddonListFactory;
use App\Modules\Front\Base\Controls\AddonModal\AddonModal;
use App\Modules\Front\Base\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Base\Controls\Search\ISearchFactory;
use App\Modules\Front\Base\Controls\Search\Search as SearchComponent;

abstract class BaseAddonPresenter extends BasePresenter
{

	/** @var ISearchFactory @inject */
	public $searchFactory;

	/** @var IAddonModalFactory @inject */
	public $addonModalFactory;

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

	protected function createComponentSearch(): SearchComponent
	{
		$search = $this->searchFactory->create();

		$search['form']->setMethod('GET');
		$search['form']->setAction($this->link(Destination::FRONT_SEARCH));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(Destination::FRONT_SEARCH, ['q' => '_QUERY_']));

		return $search;
	}

	protected function createComponentModal(): AddonModal
	{
		$control = $this->addonModalFactory->create();

		$control->onSuccess[] = function (): void {
			$this->redirect(Destination::FRONT_HOMEPAGE);
		};

		return $control;
	}

	protected function createAddonListControl(QueryObject $queryObject): AddonList
	{
		return $this->addonListFactory->create($queryObject);
	}

}
