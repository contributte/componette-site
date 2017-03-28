<?php

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractRepository;

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
