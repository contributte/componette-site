<?php

namespace App\Modules\Front\Portal\Controls\AddonMeta;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;

final class AddonMeta extends BaseControl
{

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * @param Addon $addon
	 */
	public function render(Addon $addon)
	{
		$this->template->addon = $addon;
		$this->template->setFile(__DIR__ . '/templates/full.latte');
		$this->template->render();
	}

	/**
	 * @param Addon $addon
	 */
	public function renderShort(Addon $addon)
	{
		$this->template->addon = $addon;
		$this->template->setFile(__DIR__ . '/templates/short.latte');
		$this->template->render();
	}

}
