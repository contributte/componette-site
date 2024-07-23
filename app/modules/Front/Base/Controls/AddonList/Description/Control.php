<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Description;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	public function render(Addon $addon): void
	{
		$this->template->setParameters(['addon' => $addon])->render();
	}

}
