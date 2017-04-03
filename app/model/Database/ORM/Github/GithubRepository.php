<?php

namespace App\Model\Database\ORM\Github;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read GithubMapper $mapper
 *
 * @method ICollection|Github[] findAll()
 * @method ICollection|Github[] findBy(array $conds)
 * @method Github|NULL getBy(array $conds)
 */
final class GithubRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [Github::class];
	}

}
