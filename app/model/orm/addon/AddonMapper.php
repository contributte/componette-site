<?php

namespace App\Model\ORM\Addon;

use App\Model\ORM\AbstractMapper;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class AddonMapper extends AbstractMapper
{

    /**
     * @param mixed $q
     * @param string $q
     * @return QueryBuilder
     */
    public function findByQuery($q)
    {
        $builder = $this->builder()
            ->from('[addon]', 'a')
            ->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
            ->orWhere("[a.owner] LIKE %s", "%$q%")
            ->orWhere("[a.name] LIKE %s", "%$q%")
            ->orWhere("[g.description] LIKE %s", "%$q%")
            ->groupBy('[a.id]')
            ->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

        return $builder;
    }

    /**
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function findOrdered($orderBy)
    {
        $builder = $this->builder();
        $builder->from('[addon]', 'a')
            ->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]');

        $this->applyOrder($builder, $orderBy);

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $orderBy
     */
    public function applyOrder(QueryBuilder $builder, $orderBy)
    {
        switch ($orderBy) {
            case 'push':
                $builder->orderBy('[g.pushed_at] DESC');
                break;
            case 'popularity':
                $builder->orderBy('IFNULL([g.stars], 0) * 2 + IFNULL([g.watchers], 0) + IFNULL([g.forks], 0) DESC');
                break;
        }
    }
}
