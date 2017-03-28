<?php

namespace App\Model\Database\ORM\Github;

use App\Model\Database\ORM\AbstractRepository;

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
