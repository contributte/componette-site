<?php

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class LatestAddedAddonsQuery extends QueryObject
{

	const DEFAULT_LIMIT = 8;

	/**
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function doQuery(QueryBuilder $builder)
	{
		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->orderBy('[a.created_at] DESC');

		if (!$this->limit) {
			$qb->limitBy(self::DEFAULT_LIMIT);
		}

		return $qb;
	}

}
