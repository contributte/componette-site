<?php declare (strict_types = 1);

namespace App\Model\UI;

use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @property-read DefaultTemplate $template
 */
abstract class BaseRenderControl extends BaseControl
{

	protected const DEFAULT_TEMPLATE = 'default';

	protected function createTemplate(): Template
	{
		$template = parent::createTemplate();
		$template->setFile($this->getTemplateFile());
		return $template;
	}

	final protected function getTemplateFile(?string $template = null): string
	{
		if (!$template) {
			$template = static::DEFAULT_TEMPLATE;
		}
		$file = (string)$this->getReflection()->getFileName();
		return dirname($file) . "/templates/$template.latte";
	}

}
