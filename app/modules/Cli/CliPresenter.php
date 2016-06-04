<?php

namespace App\Modules\Cli;

final class CliPresenter extends BasePresenter
{

    public function actionHi()
    {
        $this->output->outln('Hello!');
    }

}
