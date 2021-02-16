<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonMeta;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;

final class AddonMeta extends BaseControl
{

	public function render(Addon $addon): void
	{
		$this->template->addon = $addon;
		$this->template->setFile(__DIR__ . '/templates/full.latte');
		$this->template->render();
	}

	public function renderShort(Addon $addon): void
	{
		$this->template->addon = $addon;
		$this->template->setFile(__DIR__ . '/templates/short.latte');
		$this->template->render();
	}

}
