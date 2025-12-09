<?php declare(strict_types = 1);

namespace App\Model\Database\Query\Base;

use Nextras\Dbal\QueryBuilder\QueryBuilder;

interface Queryable
{

    // Hydration modes
    public const HYDRATION_RESULTSET = 1;
    public const HYDRATION_ENTITY = 2;

    public function doQuery(QueryBuilder $builder): QueryBuilder;

}
