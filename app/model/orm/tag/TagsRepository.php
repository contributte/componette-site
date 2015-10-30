<?php

namespace App\Model\ORM;

final class TagsRepository extends AbstractRepository
{

    public function fetchPairs()
    {
        return $this->findAll()->orderBy('name')->fetchPairs('id', 'name');
    }


    public function findWithHighPriority()
    {
        return $this->findBy(['priority>' => 0])->orderBy('priority', 'DESC');
    }


    public function findWithLowPriority()
    {
        return $this->findBy(['priority' => 0])->orderBy('name', 'ASC');
    }

}
