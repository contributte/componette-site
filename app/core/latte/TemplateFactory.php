<?php

namespace App\Core\Latte;

use App\Core\Latte\Filters\FiltersExecutor;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

final class TemplateFactory extends NTemplateFactory
{

    /**
     * @param Control $control
     * @return Template
     */
    public function createTemplate(Control $control = NULL)
    {
        $template = parent::createTemplate($control);
        $template->_tpl = $template;
        $template->_tplfe = new FiltersExecutor($template->getLatte());

        return $template;
    }

}
