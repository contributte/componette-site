<?php

namespace App\Modules\Base;

use App\Model\Portal;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all presenters.
 */
abstract class AbstractPresenter extends Presenter
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
        $this->template->rev = $this->portal->expand('build.rev');
        $this->template->debug = $this->portal->isDebug();
    }

}
