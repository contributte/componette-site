<?php

namespace App\Modules\Front\Portal\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Controls\AddonMeta\AddonMeta;
use Nextras\Orm\Collection\ICollection;

class AddonList extends BaseControl
{

	/** @var ICollection|Addon[] */
	protected $addons;

	/**
	 * @param ICollection $addons
	 */
	public function __construct($addons)
	{
		parent::__construct();
		$this->addons = $addons;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @return AddonMeta
	 */
	protected function createComponentMeta()
	{
		return new AddonMeta();
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function render()
	{
		$this->template->addons = $this->addons;

		$this->template->setFile(__DIR__ . '/templates/list.latte');
		$this->template->render();
	}

}
