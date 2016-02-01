<?php

namespace App\Model\ORM\Addon;

use App\Model\ORM\AbstractEntity;
use App\Model\ORM\Bower\Bower;
use App\Model\ORM\Composer\Composer;
use App\Model\ORM\Github\Github;
use App\Model\ORM\Tag\Tag;
use Nette\Utils\DateTime;
use Nextras\Orm\Relationships\ManyHasMany;

/**
 * @property int $id                    {primary}
 * @property string $type               {enum self::TYPE_*} {default self::TYPE_UNKNOWN}
 * @property string $state              {enum self::STATE_*} {default self::STATE_QUEUED}
 * @property string $owner
 * @property string $name
 * @property DateTime $createdAt        {default now}
 * @property DateTime|NULL $updatedAt
 *
 * @property string $fullname           {virtual}
 * @property string $isComposer         {virtual}
 * @property string $isBower            {virtual}
 *
 * @property Github $github             {1:1 Github::$addon}
 * @property Bower|NULL $bower          {1:1 Bower::$addon}
 * @property Composer|NULL $composer    {1:1 Composer::$addon}
 * @property ManyHasMany|Tag[] $tags    {m:n Tag::$addons, isMain=true}
 */
class Addon extends AbstractEntity
{

    const TYPE_COMPOSER = 'COMPOSER';
    const TYPE_BOWER = 'BOWER';
    const TYPE_UNTYPE = 'UNTYPE';
    const TYPE_UNKNOWN = 'UNKNOWN';

    const STATE_ACTIVE = 'ACTIVE';
    const STATE_ARCHIVED = 'ARCHIVED';
    const STATE_QUEUED = 'QUEUED';

    /**
     * @return string
     */
    protected function getterFullname()
    {
        return $this->owner . '/' . $this->name;
    }

    /**
     * @return bool
     */
    protected function getterIsComposer()
    {
        return $this->type === self::TYPE_COMPOSER;
    }

    /**
     * @return bool
     */
    protected function getterIsBower()
    {
        return $this->type === self::TYPE_BOWER;
    }
}
