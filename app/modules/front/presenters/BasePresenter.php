<?php

namespace App\Modules\Front;

use App\Model\Portal;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

    /** @var Portal @inject */
    public $portal;

    /**
     * Common template method
     */
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->portal = $this->portal;
        $this->template->rev = $this->context->parameters['build']['rev'];
        $this->template->debug = (bool)$this->context->parameters['debugMode'];
    }

}
