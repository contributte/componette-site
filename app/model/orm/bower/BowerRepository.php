<?php

namespace App\Model\ORM\Bower;

use App\Model\ORM\AbstractRepository;

final class BowerRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public static function getEntityClassNames()
    {
        return [Bower::class];
    }

}
