<?php

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read GithubReleaseMapper $mapper
 *
 * @method ICollection|GithubRelease[] findAll()
 * @method ICollection|GithubRelease[] findBy(array $conds)
 * @method GithubRelease|NULL getBy(array $conds)
 */
class GithubReleaseRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [GithubRelease::class];
	}

}
