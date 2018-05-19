<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read GithubReleaseMapper $mapper
 * @method ICollection|GithubRelease[] findAll()
 * @method ICollection|GithubRelease[] findBy(array $conds)
 * @method GithubRelease|NULL getBy(array $conds)
 */
class GithubReleaseRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [GithubRelease::class];
	}

}
