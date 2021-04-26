<?php declare(strict_types = 1);

namespace App\Modules\Front\Base;

use App\Model\Database\Query\QueryObject;
use App\Model\UI\Destination;
use App\Modules\Front\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Base\Controls\AddonList\IAddonListFactory;
use App\Modules\Front\Base\Controls\AddonModal\AddonModal;
use App\Modules\Front\Base\Controls\AddonModal\IAddonModalFactory;

abstract class BaseAddonPresenter extends BasePresenter
{

	/** @var IAddonModalFactory @inject */
	public $addonModalFactory;

	/** @var IAddonListFactory @inject */
	public $addonListFactory;

	/**
	 * Common render method
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->template->search = $this->search;
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
