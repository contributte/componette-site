<?php

namespace App\Modules\Cli;

use App\Core\Cli\Output;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

    /** @var Output */
    protected $output;

    /**
     * Common presenter method
     */
    protected function startup()
    {
        parent::startup();

        $this->output = new Output();
    }

    /**
     * Common template method
     */
    protected function beforeRender()
    {
        parent::beforeRender();
        $this->response('No rendering in CLI');
    }

    /**
     * Send response
     *
     * @param string $message
     */
    protected function response($message)
    {
        $this->sendResponse(new TextResponse($message));
    }

    /**
     * Termine CLI
     */
    protected function finish()
    {
        $this->terminate();
    }
}
