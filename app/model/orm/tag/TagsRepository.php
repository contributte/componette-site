<?php

namespace App\Model\ORM;

use Nextras\Orm\Collection\ICollection;

final class TagsRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public function fetchPairs()
    {
        return $this->findAll()->orderBy('name')->fetchPairs('id', 'name');
    }

    /**
     * @return ICollection
     */
    public function findWithHighPriority()
    {
        return $this->findBy(['priority>' => 0])->orderBy('priority', 'DESC');
    }

    /**
     * @return ICollection
     */
    public function findWithLowPriority()
    {
        return $this->findBy(['priority' => 0])->orderBy('name', 'ASC');
    }

}
