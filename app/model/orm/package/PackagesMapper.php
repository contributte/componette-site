<?php

namespace App\Model\ORM;

use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class PackagesMapper extends AbstractMapper
{

    /**
     * @param mixed $q
     * @return QueryBuilder
     */
    public function search($q, $orderBy)
    {
        $builder = $this->builder()
            ->from('[packages]', 'p')
            ->leftJoin('p', '[metadatas]', 'm', '[m.package] = [p.id]')
            ->orWhere("[p.repository] LIKE %s", "%$q%")
            ->orWhere("[m.owner] LIKE %s", "%$q%")
            ->orWhere("[m.name] LIKE %s", "%$q%")
            ->orWhere("[m.description] LIKE %s", "%$q%")
            ->leftJoin('p', '[packages_x_tags]', 'pxt', '[pxt.packages_id] = [p.id]')
            ->leftJoin('pxt', '[tags]', 't', '[t.id] = [pxt.tags_id]')
            ->orWhere('[t.name] LIKE %s', "%$q%")
            ->groupBy('[p.id]')
            //->orWhere("[m.homepage] LIKE %s", "%$q%")
            //->orWhere("[m.content] LIKE %s", "%$q%")
            ->andWhere('[p.state] = %s', Package::STATE_ACTIVE);
        $this->applyOrder($builder, $orderBy);

        return $builder;
    }

    /**
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function findOrdered($orderBy)
    {
        $builder = $this->builder();
        $builder->from('[packages]', 'p')
            ->leftJoin('p', '[metadatas]', 'm', '[m.package] = [p.id]');

        $this->applyOrder($builder, $orderBy);

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $orderBy
     */
    private function applyOrder(QueryBuilder $builder, $orderBy)
    {
        switch ($orderBy) {
            case 'push':
                $builder->orderBy('[m.pushed] DESC');
                break;
            case 'popularity':
                $builder->orderBy('IFNULL([m.stars], 0) * 1.5 + POW(IFNULL([m.downloads], 0), 0.4) DESC');
                break;
        }
    }
}
