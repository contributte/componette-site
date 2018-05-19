<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nextras\Orm\Relationships\ManyHasMany;

/**
 * @property int $id                        {primary}
 * @property string $name
 * @property string|NULL $color
 * @property int $priority
 * @property bool $highlighted
 *
 * @property ManyHasMany|Addon[] $addons    {m:m Addon::$tags}
 */
class Tag extends AbstractEntity
{

}
