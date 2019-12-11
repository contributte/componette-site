<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read TagMapper $mapper
 * @method ICollection|Tag[] findAll()
 * @method ICollection|Tag[] findBy(array $conds)
 * @method Tag|NULL getBy(array $conds)
 * @extends AbstractRepository<Tag>
 */
final class TagRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [Tag::class];
	}

}
