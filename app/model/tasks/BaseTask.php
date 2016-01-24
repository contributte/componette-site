<?php

namespace App\Model\Tasks;

use Tracy\Debugger;

abstract class BaseTask
{

    /**
     * @param string $message
     * @return void
     */
    protected function log($message)
    {
        Debugger::log($message, 'tasks' . date('d-m-Y'));
    }

    /**
     * @param array $args
     * @return mixed
     */
    abstract public function run(array $args = []);

}
