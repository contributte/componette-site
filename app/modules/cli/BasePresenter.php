<?php

namespace App\Modules\Cli;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->error('No rendering in CLI');
    }

    /**
     * @param string $message
     */
    protected function info($message)
    {
        $this->sendResponse(new TextResponse($message));
    }
}
