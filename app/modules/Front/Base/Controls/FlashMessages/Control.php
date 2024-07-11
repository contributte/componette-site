<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\FlashMessages;

use App\Model\UI\BaseControl;

class Control extends BaseControl
{

	public const TYPE_TO_CLASS = [
		'danger' => 'bg-red-100 border-red-400 text-red-700',
		'info' => 'bg-teal-100 border-teal-400 text-teal-700',
		'warning' => 'bg-orange-100 border-orange-400 text-orange-700',
	];

	public function render(): void
	{
		$this->template
			->setParameters(['flashMessages' => $this->presenter->getFlashSession()->offsetGet('flash') ?? []])
			->render(__DIR__ . '/templates/default.latte');
	}

}
