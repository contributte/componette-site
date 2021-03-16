<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base;

use App\Model\UI\Destination;
use App\Modules\Front\Base\BasePresenter as BaseFrontPresenter;
use App\Modules\Front\Portal\Base\Controls\AddonModal\AddonModal;
use App\Modules\Front\Portal\Base\Controls\AddonModal\IAddonModalFactory;
use App\Modules\Front\Portal\Base\Controls\Search\ISearchFactory;
use App\Modules\Front\Portal\Base\Controls\Search\Search;

/**
 * Base presenter for all portal presenters.
 */
abstract class BasePresenter extends BaseFrontPresenter
{

	/** @var ISearchFactory @inject */
	public $searchFactory;

	/** @var IAddonModalFactory @inject */
	public $addonModalFactory;

	protected function createComponentSearch(): Search
	{
		$search = $this->searchFactory->create();

		$search['form']->setMethod('GET');
		$search['form']->setAction($this->link(Destination::FRONT_PORTAL_SEARCH));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(Destination::FRONT_PORTAL_SEARCH, ['q' => '_QUERY_']));

		return $search;
	}

	protected function createComponentModal(): AddonModal
	{
		$control = $this->addonModalFactory->create();

		$control->onSuccess[] = function (): void {
			$this->redirect(Destination::FRONT_PORTAL_HOMEPAGE);
		};

		return $control;
	}

}
