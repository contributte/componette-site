<?php

namespace App\Model\ORM;

use Nette\Utils\DateTime;
use Nextras\Orm\Relationships\ManyHasMany;

/**
 * @property Metadata           $metadata           {1:1d Metadata::$package}
 * @property ManyHasMany|Tag[]  $tags               {m:n Tag::$packages primary}
 * @property string             $type               {enum self::TYPE_*}	{default self::TYPE_UNKNOWN}
 * @property string             $state              {enum self::STATE_*} {default self::STATE_QUEUED}
 * @property string             $repository
 * @property DateTime           $created            {default now}
 * @property DateTime|NULL      $updated
 * @property string             $isComposerPackage  {virtual}
 * @property string             $isBowerPackage     {virtual}
 */
class Package extends AbstractEntity
{

    const TYPE_COMPOSER = 'COMPOSER';
    const TYPE_BOWER = 'BOWER';
    const TYPE_UNKNOWN = 'UNKNOWN';

    const STATE_ACTIVE = 'ACTIVE';
    const STATE_ARCHIVED = 'ARCHIVED';
    const STATE_QUEUED = 'QUEUED';

    /**
     * @return bool
     */
    protected function getterIsComposerPackage()
    {
        return $this->type === self::TYPE_COMPOSER;
    }
    /**
     * @return bool
     */
    protected function getterIsBowerPackage()
    {
        return $this->type === self::TYPE_BOWER;
    }
}
