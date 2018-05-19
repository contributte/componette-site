<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Composer;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read ComposerMapper $mapper
 * @method ICollection|Composer[] findAll()
 * @method ICollection|Composer[] findBy(array $conds)
 * @method Composer|NULL getBy(array $conds)
 */
final class ComposerRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [Composer::class];
	}

}
