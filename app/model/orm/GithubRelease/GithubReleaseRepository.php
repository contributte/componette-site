<?php

namespace App\Model\ORM\GithubRelease;

use App\Model\ORM\AbstractRepository;

class GithubReleaseRepository extends AbstractRepository
{

    /**
     * @return array
     */
    public static function getEntityClassNames()
    {
        return [GithubRelease::class];
    }

}
