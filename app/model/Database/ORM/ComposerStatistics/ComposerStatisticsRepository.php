<?php

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractRepository;

final class ComposerStatisticsRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [ComposerStatistics::class];
	}

}
