<?php

namespace App\Modules\Cli;

use App\Tasks\Packages\GenerateContentTask;
use App\Tasks\Packages\UpdateComposerTask;
use App\Tasks\Packages\UpdateMetadataTask;

final class PackagesPresenter extends BasePresenter
{

    /** @var GenerateContentTask @inject */
    public $generateContentTask;

    /** @var UpdateMetadataTask @inject */
    public $updateMetadataTask;

    /** @var UpdateComposerTask @inject */
    public $updateComposerTask;

    public function actionUpdate()
    {
        if ($this->getParameter('metadata', FALSE)) {
            $this->info($this->updateMetadataTask->run($this->getParameters()));
        } else if ($this->getParameter('composer', FALSE)) {
            $this->info($this->updateComposerTask->run($this->getParameters()));
        } else {
            $this->info('Select type');
        }
    }

    public function actionGenerate()
    {
        if ($this->getParameter('content', FALSE)) {
            $this->info($this->generateContentTask->run($this->getParameters()));
        } else {
            $this->info('Select type');
        }
    }
}
