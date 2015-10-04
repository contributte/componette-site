<?php

namespace App\Model\ORM;

use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class PackagesMapper extends AbstractMapper
{

    /**
     * @param mixed $q
     * @return QueryBuilder
     */
    public function search($q)
    {
        return $this->builder()
            ->from('[packages]', 'p')
            ->leftJoin('p', '[metadatas]', 'm', '[m.package] = [p.id]')
            ->orWhere("[p.repository] LIKE %s", "%$q%")
            ->orWhere("[m.owner] LIKE %s", "%$q%")
            ->orWhere("[m.name] LIKE %s", "%$q%")
            ->orWhere("[m.description] LIKE %s", "%$q%")
            //->orWhere("[m.homepage] LIKE %s", "%$q%")
            //->orWhere("[m.content] LIKE %s", "%$q%")
            ->andWhere('[p.state] = %s', Package::STATE_ACTIVE);
    }

}
