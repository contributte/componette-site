<?php

namespace App\Model\Templating;

use App\Model\Portal;
use App\Model\Templating\Filters\Helpers;
use App\Model\Templating\Filters\HelpersExecutor;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

final class TemplateFactory extends NTemplateFactory
{

	/** @var Portal @inject */
	public $portal;

	/** @var Portal @inject */
	public $rawgit;

	public function createTemplate(Control $control = NULL): ITemplate
	{
		/** @var Template $template */
		$template = parent::createTemplate($control);

		// Template tweaks!
		$template->_helpers = $helpers = new HelpersExecutor();
		$helpers->addHelper('isPhp', [Helpers::class, 'isPhp']);

		// Common variables
		$template->_debug = $this->portal->isDebug();

		return $template;
	}

}
