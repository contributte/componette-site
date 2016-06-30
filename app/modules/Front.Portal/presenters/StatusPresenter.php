<?php

namespace App\Modules\Front\Portal;

use App\Modules\Front\Portal\Controls\Status\IStatusFactory;
use App\Modules\Front\Portal\Controls\Status\Status;

final class StatusPresenter extends BasePresenter
{

    /** @var IStatusFactory @inject */
    public $statusFactory;

    /**
     * @return Status
     */
    protected function createComponentStatus()
    {
        return $this->statusFactory->create();
    }

}
