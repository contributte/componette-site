<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base;

use App\Model\Services\Search\Search;
use App\Modules\Front\Portal\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Portal\Base\Controls\AddonList\IAddonListFactory;
use Nextras\Orm\Collection\ICollection;

abstract class BaseAddonPresenter extends BasePresenter
{

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

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @phpstan-param ICollection<\App\Model\Database\ORM\Addon\Addon> $addons
	 */
	protected function createAddonListControl(ICollection $addons): AddonList
	{
		return $this->addonListFactory->create($addons);
	}

}
