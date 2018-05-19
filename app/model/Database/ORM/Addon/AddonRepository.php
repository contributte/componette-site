<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read AddonMapper $mapper
 * @method ICollection|Addon[] findAll()
 * @method ICollection|Addon[] findBy(array $conds)
 * @method Addon|NULL getBy(array $conds)
 */
final class AddonRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [Addon::class];
	}

}
