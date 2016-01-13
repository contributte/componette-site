<?php

namespace App\Model\ORM\Tag;

use App\Model\ORM\AbstractEntity;
use App\Model\ORM\Addon\Addon;
use Nextras\Orm\Relationships\ManyHasMany;

/**
 * @property int $id                        {primary}
 * @property string $name
 * @property string|NULL $color
 * @property int $priority
 *
 * @property ManyHasMany|Addon[] $addons    {m:n Addon::$tags}
 */
class Tag extends AbstractEntity
{

}
