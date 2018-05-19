<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class LatestActivityAddonsQuery extends QueryObject
{

	public const DEFAULT_LIMIT = 5;

	public function doQuery(QueryBuilder $builder): QueryBuilder
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
