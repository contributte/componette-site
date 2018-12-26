<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class LatestReleaseIdsQuery extends QueryObject
{

	public function doQuery(QueryBuilder $builder): QueryBuilder
	{
		$qb = $builder->select('MAX([gr.published_at]), MAX(id) as id')
			->from('[github_release]', 'gr')
			->groupBy('[gr.github_id]')
			->orderBy('[gr.published_at] DESC');

		return $qb;
	}

}
