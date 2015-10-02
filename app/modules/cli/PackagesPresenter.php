<?php

namespace App\Modules\Cli;

use App\Tasks\Packages\GenerateContentsTask;
use App\Tasks\Packages\UpdatePackagesTask;

final class PackagesPresenter extends BasePresenter
{

    /** @var GenerateContentsTask @inject */
    public $generateContentTask;

    /** @var UpdatePackagesTask @inject */
    public $updatePackageTask;

    public function actionUpdatePackages()
    {
        $this->info($this->updatePackageTask->run($this->getParameters()));
    }

    public function actionGenerateContents()
    {
        $this->info($this->generateContentTask->run($this->getParameters()));
    }
}
