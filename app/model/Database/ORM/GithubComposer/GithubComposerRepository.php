<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read GithubComposerMapper $mapper
 * @method ICollection|GithubComposer[] findAll()
 * @method ICollection|GithubComposer[] findBy(array $conds)
 * @method GithubComposer|NULL getBy(array $conds)
 */
final class GithubComposerRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [GithubComposer::class];
	}

}
