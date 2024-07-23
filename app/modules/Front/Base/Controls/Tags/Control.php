<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Tags;

use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	use InjectTags;

	public function render(): void
	{
		$this->template->setParameters(['tags' => $this->tags->get()])->render();
	}

}
