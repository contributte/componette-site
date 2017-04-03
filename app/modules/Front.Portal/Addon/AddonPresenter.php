<?php

namespace App\Modules\Front\Portal\Addon;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Facade\AddonFacade;
use App\Modules\Front\Portal\Addon\Controls\AddonDetail\AddonDetail;
use App\Modules\Front\Portal\Addon\Controls\AddonDetail\IAddonDetailFactory;
use App\Modules\Front\Portal\Base\BasePresenter;

final class AddonPresenter extends BasePresenter
{

	/** @var AddonFacade @inject */
	public $addonFacade;

	/** @var IAddonDetailFactory @inject */
	public $addonDetailFactory;

	/** @var Addon */
	protected $addon;

	/**
	 * @param int $slug
	 * @return void
	 */
	public function actionDetail($slug)
	{
		$this->addon = $this->addonFacade->getById($slug);
		if (!$this->addon) {
			$this->error('Addon not found');
		}
	}

	/**
	 * Display addon detail
	 *
	 * @return void
	 */
	public function renderDetail()
	{
		$this->template->addon = $this->addon;
	}

	/**
	 * @return AddonDetail
	 */
	protected function createComponentAddon(): AddonDetail
	{
		return $this->addonDetailFactory->create($this->addon);
	}

}
