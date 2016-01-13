<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\AddonRepository;
use App\Model\Tasks\BaseTask;

abstract class BaseAddonTask extends BaseTask
{

    /** @var AddonRepository */
    protected $addonRepository;

    /**
     * @param AddonRepository $addonRepository
     */
    public function __construct(AddonRepository $addonRepository)
    {
        $this->addonRepository = $addonRepository;
    }

}
