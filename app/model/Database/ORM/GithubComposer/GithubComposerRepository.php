<?php

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractRepository;

final class GithubComposerRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [GithubComposer::class];
	}

}
