<?php declare(strict_types = 1);

namespace App\Model\Database\Query\Base;

use Nextras\Dbal\QueryBuilder\QueryBuilder;

abstract class QueryObjectBase implements Queryable
{

    protected function postQuery(QueryBuilder $builder): QueryBuilder
    {
        return $builder;
    }

    public function fetch(QueryBuilder $builder): QueryBuilder
    {
        // Build query
        $qb = $this->doQuery($builder);

        // Decorate query
        $qb = $this->postQuery($qb);

        return $qb;
    }

}
