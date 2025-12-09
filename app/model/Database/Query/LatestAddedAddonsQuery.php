<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class LatestAddedAddonsQuery extends QueryObject
{

    public const DEFAULT_LIMIT = 9;

    public function doQuery(QueryBuilder $builder): QueryBuilder
    {
        $qb = $builder->select('a.*')
            ->from('[addon]', 'a')
            ->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
            ->orderBy('[a.created_at] DESC');

        if (!$this->limit) {
            $qb->limitBy(self::DEFAULT_LIMIT);
        }

        return $qb;
    }

}
