<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use App\Model\Database\Query\QueryObject;
use Contributte\Nextras\Orm\QueryObject\Repository\TRepositoryQueryable;
use Nextras\Dbal\Result\Result;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Repository\Repository;

abstract class AbstractRepository extends Repository
{

	use TRepositoryQueryable;

	public function fetchEntities(QueryObject $query): ICollection
	{
		/** @var ICollection|IEntity[] $collection */
		$collection = $this->fetch($query, QueryObject::HYDRATION_ENTITY);

		return $collection;
	}

	public function fetchResult(QueryObject $query): Result
	{
		/** @var Result|IEntity[] $result */
		$result = $this->fetch($query, QueryObject::HYDRATION_RESULTSET);

		return $result;
	}

}
