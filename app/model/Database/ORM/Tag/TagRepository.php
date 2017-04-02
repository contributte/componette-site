<?php

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractRepository;

final class TagRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [Tag::class];
	}

}
