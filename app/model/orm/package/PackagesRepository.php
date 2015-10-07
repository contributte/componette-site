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
