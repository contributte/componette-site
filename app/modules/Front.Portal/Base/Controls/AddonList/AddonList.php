<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Base\Controls\AddonMeta\AddonMeta;
use Nextras\Orm\Collection\ICollection;

class AddonList extends BaseControl
{

	/** @var ICollection|Addon[] */
	protected $addons;

	public function __construct(ICollection $addons)
	{
		parent::__construct();
		$this->addons = $addons;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	protected function createComponentMeta(): AddonMeta
	{
		return new AddonMeta();
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function render(): void
	{
		$this->template->addons = $this->addons;

		$this->template->setFile(__DIR__ . '/templates/list.latte');
		$this->template->render();
	}

}
