<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class ComponettersQuery extends QueryObject
{

	public function doQuery(QueryBuilder $builder): QueryBuilder
	{
		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->groupBy('[a.author]')
			->orderBy('[a.id] DESC');

		return $qb;
	}

}
