<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Tags;

use App\Model\UI\BaseRenderControl;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BaseRenderControl
{

	use InjectTags;

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->setParameters(['tags' => $this->tags->get()]);
	}

}
