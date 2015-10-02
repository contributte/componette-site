<?php

namespace App\Model\ORM;
use Nette\Utils\DateTime;

/**
 * @property Metadata   $metadata           {1:1d Metadata::$package}
 * @property string     $type               {enum self::TYPE_*}
 * @property string     $state              {enum self::STATE_*}
 * @property string     $repository
 * @property DateTime   $created            {default now}
 * @property DateTime   $updated            {default now}
 * @property string     $isComposerPackage  {virtual}
 */
class Package extends AbstractEntity
{

    const TYPE_COMPOSER = 'COMPOSER';
    const TYPE_BOWER = 'BOWER';

    const STATE_ACTIVE = 'ACTIVE';
    const STATE_ARCHIVED = 'ARCHIVED';

    /**
     * @return bool
     */
    protected function getterIsComposerPackage()
    {
        return $this->type === self::TYPE_COMPOSER;
    }
}
