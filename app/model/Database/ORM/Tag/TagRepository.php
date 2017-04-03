<?php

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read TagMapper $mapper
 *
 * @method ICollection|Tag[] findAll()
 * @method ICollection|Tag[] findBy(array $conds)
 * @method Tag|NULL getBy(array $conds)
 */
final class TagRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [Tag::class];
	}

}
