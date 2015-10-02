<?php

namespace App\Model\ORM;

use Nextras\Orm\Collection\ICollection;

/**
 * @method ICollection|Package[] search()
 */
final class PackagesRepository extends AbstractRepository
{

    /**
     * @return ICollection
     */
    public function findActive()
    {
        return $this->findBy(['state' => Package::STATE_ACTIVE]);
    }

    /**
     * @return ICollection
     */
    public function findActiveBy(array $conds)
    {
        return $this->findActive()->findBy($conds);
    }

}
