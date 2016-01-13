<?php

namespace App\Model\ORM\Bower;

use App\Model\ORM\AbstractEntity;
use App\Model\ORM\Addon\Addon;
use Nette\Utils\DateTime;

/**
 * @property int $id                {primary}
 * @property Addon $addon           {1:1 Addon::$bower, isMain=true}
 * @property int|NULL $downloads
 * @property string|NULL $keywords
 * @property DateTime $crawledAt    {default now}
 */
class Bower extends AbstractEntity
{

    /**
     * Called before persist to storage
     *
     * @return void
     */
    protected function onBeforePersist()
    {
        parent::onBeforePersist();

        $this->crawledAt = new DateTime();
    }

}
