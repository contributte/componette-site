<?php

namespace App\Model\ORM;

use Nextras\Orm\Relationships\ManyHasMany;

/**
 * @property string $name
 * @property ManyHasMany|Package[] $packages       {m:n Package::$tags}
 * @property string|NULL $color
 * @property int $priority
 */
class Tag extends AbstractEntity
{


}
