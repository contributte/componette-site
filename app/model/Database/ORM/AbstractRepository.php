<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use Contributte\Nextras\Orm\QueryObject\Repository\TRepositoryQueryable;
use Nextras\Orm\Repository\Repository;

abstract class AbstractRepository extends Repository
{

	use TRepositoryQueryable;

}
