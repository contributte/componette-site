<?php

namespace App\Model\Database\ORM\Bower;

use App\Model\Database\ORM\AbstractRepository;

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
