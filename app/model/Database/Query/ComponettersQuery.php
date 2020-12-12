<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class ComponettersQuery extends QueryObject
{

	public function doQuery(QueryBuilder $builder): QueryBuilder
	{
		return $builder->select('a.*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->groupBy('[a.author]')
			->orderBy('[a.id] DESC');
	}

}
