<?php declare(strict_types = 1);

namespace App\Modules\Front\Addon;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Facade\AddonFacade;
use App\Modules\Front\Addon\Controls\AddonDetail\AddonDetail;
use App\Modules\Front\Addon\Controls\AddonDetail\IAddonDetailFactory;
use App\Modules\Front\Base\BaseAddonPresenter;

final class AddonPresenter extends BaseAddonPresenter
{

	/** @var AddonFacade @inject */
	public $addonFacade;

	/** @var IAddonDetailFactory @inject */
	public $addonDetailFactory;

	/** @var Addon */
	protected $addon;

	public function actionDetail(int $slug): void
	{
		$this->addon = $this->getAddon($slug);
	}

	public function renderDetail(): void
	{
		$this->template->addon = $this->addon;
		$this->template->tabs = ['content' => 'Readme', 'releases' => 'Releases', 'comments' => 'Comments'];
	}

	protected function createComponentAddon(): AddonDetail
	{
		return $this->addonDetailFactory->create($this->addon);
	}

	private function getAddon(int $id): Addon
	{
		if (!($addon = $this->addonFacade->getDetail($id))) {
			$this->error('Addon not found');
		}

		return $addon;
	}

}
