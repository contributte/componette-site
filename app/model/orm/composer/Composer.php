<?php

namespace App\Model\ORM\Composer;

use App\Model\ORM\AbstractEntity;
use App\Model\ORM\Addon\Addon;
use Nette\Utils\DateTime;

/**
 * @property int $id                {primary}
 * @property Addon $addon           {1:1 Addon::$composer, isMain=true}
 * @property string $name
 * @property string|NULL $description
 * @property string|NULL $type
 * @property int|NULL $downloads
 * @property string|NULL $keywords
 * @property DateTime $crawledAt    {default now}
 */
class Composer extends AbstractEntity
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
