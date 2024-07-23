<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Avatar;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	public function render(Addon $addon, bool $linkToGitHub = false, bool $small = false): void
	{
		$this->template->setParameters([
			'addon' => $addon,
			'linkToGitHub' => $linkToGitHub,
			'small' => $small,
		])->render();
	}

}
