<?php

namespace App\Model\ORM\Github;

use App\Model\ORM\AbstractRepository;

final class GithubRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public static function getEntityClassNames()
    {
        return [Github::class];
    }

}
