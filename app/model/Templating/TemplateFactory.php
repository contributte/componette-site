<?php

namespace App\Model\Templating;

use App\Model\AppParams;
use App\Model\Templating\Filters\Helpers;
use App\Model\Templating\Filters\HelpersExecutor;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

final class TemplateFactory extends NTemplateFactory
{

	/** @var AppParams @inject */
	public $appParams;

	/** @var AppParams @inject */
	public $rawgit;

	public function createTemplate(Control $control = null, string $class = null): Template
	{
		/** @var Template $template */
		$template = parent::createTemplate($control);

		// Template tweaks!
		$template->_helpers = $helpers = new HelpersExecutor();
		$helpers->addHelper('isPhp', [Helpers::class, 'isPhp']);

		// Common variables
		$template->_debug = $this->appParams->isDebug();

		return $template;
	}

}
