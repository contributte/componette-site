<?php declare(strict_types = 1);

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

	public function actionDetail(int $slug): void
	{
		$this->addon = $this->addonFacade->getDetail($slug);
		if (!$this->addon) {
			$this->error('Addon not found');
		}
	}

	/**
	 * Display addon detail
	 */
	public function renderDetail(): void
	{
		$this->template->addon = $this->addon;
	}

	protected function createComponentAddon(): AddonDetail
	{
		return $this->addonDetailFactory->create($this->addon);
	}

}
