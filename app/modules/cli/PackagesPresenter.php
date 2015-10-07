<?php

namespace App\Modules\Cli;

use App\Tasks\Packages\GenerateContentTask;
use App\Tasks\Packages\UpdateBowerTask;
use App\Tasks\Packages\UpdateComposerTask;
use App\Tasks\Packages\UpdateGithubTask;
use App\Tasks\Packages\UpdateMetadataTask;

final class PackagesPresenter extends BasePresenter
{

    /** @var GenerateContentTask @inject */
    public $generateContentTask;

    /** @var UpdateMetadataTask @inject */
    public $updateMetadataTask;

    /** @var UpdateComposerTask @inject */
    public $updateComposerTask;

    /** @var UpdateGithubTask @inject */
    public $updateGithubTask;

    /** @var UpdateBowerTask @inject */
    public $updateBowerTask;

    /**
     * Packages:update *********************************************************
     * *************************************************************************
     */

    public function actionUpdate()
    {
        $this->output->outln('Packages:update');

        if ($this->getParameter('metadata', FALSE)) {

            $this->output->outln('* running [UpdateMetadata]');
            $res = $this->updateMetadataTask->run($this->getParameters());
            $this->output->outln('* result [UpdateMetadata](' . $res . ')');

        } else if ($this->getParameter('composer', FALSE)) {

            $this->output->outln('* running [UpdateComposer]');
            $res = $this->updateComposerTask->run($this->getParameters());
            $this->output->outln('* result [UpdateComposer](' . $res . ')');

        } else if ($this->getParameter('github', FALSE)) {

            $this->output->outln('* running [UpdateGithub]');
            $res = $this->updateGithubTask->run($this->getParameters());
            $this->output->outln('* result [UpdateGithub](' . $res . ')');

        } else if ($this->getParameter('bower', FALSE)) {

            $this->output->outln('* running [UpdateBower]');
            $res = $this->updateBowerTask->run($this->getParameters());
            $this->output->outln('* result [UpdateBower](' . $res . ')');

        } else {
            $this->output->outln('- select type (-metadata | -github | -composer | -bower)');
        }

        $this->finish();
    }

    /**
     * Packages:generate *******************************************************
     * *************************************************************************
     */

    public function actionGenerate()
    {
        $this->output->outln('Packages:generate');

        if ($this->getParameter('content', FALSE)) {

            $this->output->outln('* r [GenerateContent]');
            $res = $this->generateContentTask->run($this->getParameters());
            $this->output->outln('* result [GenerateContent](' . $res . ')');

        } else {
            $this->output->outln('- select type (-content)');
        }

        $this->finish();
    }
}
