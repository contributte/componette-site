<?php

namespace App\Tasks;

use Tracy\Debugger;

abstract class BaseTask
{

    /**
     * @param string $message
     * @return void
     */
    protected function log($message)
    {
        Debugger::log($message, 'tasks');
    }
}
