<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read ComposerStatisticsMapper $mapper
 * @method ICollection|ComposerStatistics[] findAll()
 * @method ICollection|ComposerStatistics[] findBy(array $conds)
 * @method ComposerStatistics|NULL getBy(array $conds)
 * @extends AbstractRepository<ComposerStatistics>
 */
final class ComposerStatisticsRepository extends AbstractRepository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [ComposerStatistics::class];
	}

}
