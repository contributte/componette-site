<?php

namespace App\Model\Database\ORM\Composer;

use App\Model\Database\ORM\AbstractRepository;

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
