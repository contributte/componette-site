<?php

namespace App\Model\ORM\Composer;

use App\Model\ORM\AbstractRepository;

final class ComposerRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public static function getEntityClassNames()
    {
        return [Composer::class];
    }

}
