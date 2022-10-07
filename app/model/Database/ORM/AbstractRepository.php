<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use App\Model\Database\Query\QueryObject;
use Contributte\Nextras\Orm\QueryObject\Repository\TRepositoryQueryable;
use Nextras\Dbal\Result\Result;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * @template TEntityClass
 */
abstract class AbstractRepository extends Repository
{

	use TRepositoryQueryable;

	/**
	 * @phpstan-return ICollection<TEntityClass>
	 */
	public function fetchEntities(QueryObject $query): ICollection
	{
		/** @var ICollection<TEntityClass> $collection */
		$collection = $this->fetch($query, QueryObject::HYDRATION_ENTITY);

		assert($collection instanceof ICollection);

		return $collection;
	}

	/**
	 * @phpstan-return Result<TEntityClass>
	 */
	public function fetchResult(QueryObject $query): Result
	{
		/** @var Result<TEntityClass> $result */
		$result = $this->fetch($query, QueryObject::HYDRATION_RESULTSET);

		assert($result instanceof Result);

		return $result;
	}

	/**
	 * @phpstan-return array<int, class-string<TEntityClass>>
	 * @return string[]
	 */
	abstract public static function getEntityClassNames(): array;

}
