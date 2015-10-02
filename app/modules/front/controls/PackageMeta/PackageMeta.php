<?php

namespace App\Modules\Front\Controls\PackageMeta;

use App\Core\UI\BaseControl;
use App\Model\ORM\Package;

class PackageMeta extends BaseControl
{

    /**
     * RENDER ******************************************************************
     */

    /**
     * @param Package $package
     */
    public function render(Package $package)
    {
        $this->template->package = $package;
        $this->template->setFile(__DIR__ . '/templates/full.latte');
        $this->template->render();
    }

    /**
     * @param Package $package
     */
    public function renderShort(Package $package)
    {
        $this->template->package = $package;
        $this->template->setFile(__DIR__ . '/templates/short.latte');
        $this->template->render();
    }

}