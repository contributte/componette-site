<?php

namespace App\Model\Tasks\Avatars;

use App\Model\ORM\Addon\AddonRepository;
use App\Model\Tasks\BaseTask;

abstract class BaseAvatarTask extends BaseTask
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
