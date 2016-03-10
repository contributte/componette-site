<?php

namespace App\Modules\Cli;

use App\Model\Tasks\Avatars\UpdateAvatarTask;

final class AvatarsPresenter extends BasePresenter
{

    /** @var UpdateAvatarTask @inject */
    public $updateAvatarTask;

    public function actionUpdate()
    {
        $this->output->outln('Avatars:update');

        $this->output->outln('* running [UpdateAvatar]');
        $res = $this->updateAvatarTask->run($this->getParameters());
        $this->output->outln('* result [UpdateAvatar](' . $res . ')');

        $this->finish();
    }


}
