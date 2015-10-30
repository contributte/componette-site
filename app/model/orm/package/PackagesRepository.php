<?php

namespace App\Model\ORM;

use Nextras\Orm\Collection\ICollection;

/**
 * @method ICollection|Package[] search($q, $orderBy)
 * @method ICollection|Package[] findOrdered($orderBy)
 */
final class PackagesRepository extends AbstractRepository
{

    /**
     * @param string|null $orderBy
     * @return ICollection
     */
    public function findActive($orderBy = NULL)
    {
        if ($orderBy !== NULL) {
            $collection = $this->findOrdered($orderBy);
        } else {
            $collection = $this->findAll();
        }

        return $collection->findBy(['state' => Package::STATE_ACTIVE]);
    }

    /**
     * @return ICollection
     */
    public function findComposers()
    {
        return $this->findActive()->findBy(['type' => Package::TYPE_COMPOSER]);
    }

    /**
     * @return ICollection
     */
    public function findBowers()
    {
        return $this->findActive()->findBy(['type' => Package::TYPE_BOWER]);
    }

    /**
     * @return ICollection
     */
    public function findUnknowns()
    {
        return $this->findActive()->findBy(['type' => Package::TYPE_UNKNOWN]);
    }

}
