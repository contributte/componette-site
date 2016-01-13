<?php

namespace App\Model\ORM\Addon;

use App\Model\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read AddonMapper $mapper
 */
final class AddonRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public static function getEntityClassNames()
    {
        return [Addon::class];
    }

    /**
     * @param string $q
     * @param string|NULL $orderBy
     * @return ICollection|Addon[]
     */
    public function findByQuery($q, $orderBy = NULL)
    {
        $result = $this->mapper->findByQuery($q);

        if ($orderBy) {
            $this->mapper->applyOrder($result, $orderBy);
        }

        return $this->mapper->toCollection($result);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findActive()
    {
        return $this->findBy(['state' => Addon::STATE_ACTIVE]);
    }

    /**
     * @param string $orderBy
     * @return ICollection|Addon[]
     */
    public function findOrdered($orderBy)
    {
        $result = $this->mapper->findOrdered($orderBy);
        return $this->mapper->toCollection($result);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findComposers()
    {
        return $this->findActive()->findBy(['type' => Addon::TYPE_COMPOSER]);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findBowers()
    {
        return $this->findActive()->findBy(['type' => Addon::TYPE_BOWER]);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findUnknowns()
    {
        return $this->findActive()->findBy(['type' => Addon::TYPE_UNKNOWN]);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findUntypes()
    {
        return $this->findActive()->findBy(['type' => Addon::TYPE_UNTYPE]);
    }

}
