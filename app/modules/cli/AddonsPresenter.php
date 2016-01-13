<?php

namespace App\Modules\Cli;

use App\Model\Tasks\Addons\GenerateContentTask;
use App\Model\Tasks\Addons\StatsComposerTask;
use App\Model\Tasks\Addons\UpdateBowerTask;
use App\Model\Tasks\Addons\UpdateComposerTask;
use App\Model\Tasks\Addons\UpdateGithubFilesTask;
use App\Model\Tasks\Addons\UpdateGithubTask;

final class AddonsPresenter extends BasePresenter
{

    /** @var GenerateContentTask @inject */
    public $generateContentTask;

    /** @var StatsComposerTask @inject */
    public $statsComposerTask;

    /** @var UpdateGithubTask @inject */
    public $updateGithubTask;

    /** @var UpdateComposerTask @inject */
    public $updateComposerTask;

    /** @var UpdateGithubFilesTask @inject */
    public $updateGithubFilesTask;

    /** @var UpdateBowerTask @inject */
    public $updateBowerTask;

    public function actionUpdate()
    {
        $this->output->outln('Addons:update');

        if ($this->getParameter('github', FALSE)) {

            $this->output->outln('* running [UpdateGithub]');
            $res = $this->updateGithubTask->run($this->getParameters());
            $this->output->outln('* result [UpdateGithub](' . $res . ')');

        } else if ($this->getParameter('github-files', FALSE)) {

            $this->output->outln('* running [UpdateGithubFiles]');
            $res = $this->updateGithubFilesTask->run($this->getParameters());
            $this->output->outln('* result [UpdateGithubFiles](' . $res . ')');

        } else if ($this->getParameter('composer', FALSE)) {

            $this->output->outln('* running [UpdateComposer]');
            $res = $this->updateComposerTask->run($this->getParameters());
            $this->output->outln('* result [UpdateComposer](' . $res . ')');

        } else if ($this->getParameter('bower', FALSE)) {

            $this->output->outln('* running [UpdateBower]');
            $res = $this->updateBowerTask->run($this->getParameters());
            $this->output->outln('* result [UpdateBower](' . $res . ')');

        } else {
            $this->output->outln('- select type (-github | -github-files | -composer | -bower)');
        }

        $this->finish();
    }

    public function actionGenerate()
    {
        $this->output->outln('Addons:generate');

        if ($this->getParameter('content', FALSE)) {

            $this->output->outln('* running [GenerateContent]');
            $res = $this->generateContentTask->run($this->getParameters());
            $this->output->outln('* result [GenerateContent](' . $res . ')');

        } else {
            $this->output->outln('- select type (-content)');
        }

        $this->finish();
    }

    public function actionStats()
    {
        $this->output->outln('Addons:stats');

        if ($this->getParameter('composer', FALSE)) {

            $this->output->outln('* running [StatsComposer]');
            $res = $this->statsComposerTask->run($this->getParameters());
            $this->output->outln('* result [StatsComposer](' . $res . ')');

        } else {
            $this->output->outln('- select type (-stats)');
        }

        $this->finish();
    }

}
