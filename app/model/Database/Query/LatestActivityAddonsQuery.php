<?php

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class LatestActivityAddonsQuery extends QueryObject
{

	const DEFAULT_LIMIT = 5;

	/**
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function doQuery(QueryBuilder $builder)
	{
		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->addOrderBy('[g.pushed_at] DESC')
			->addOrderBy('[a.updated_at] DESC');

		if (!$this->limit) {
			$qb->limitBy(self::DEFAULT_LIMIT);
		}

		return $qb;
	}

}
