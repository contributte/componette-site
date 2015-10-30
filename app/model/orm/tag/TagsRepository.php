<?php

namespace App\Model\ORM;

final class TagsRepository extends AbstractRepository
{

    public function fetchPairs()
    {
        return $this->findAll()->orderBy('name')->fetchPairs('id', 'name');
    }
}
