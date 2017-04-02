<?php

namespace App\Model\Database\ORM;

use Minetro\Nextras\Orm\QueryObject\Repository\TRepositoryQueryable;
use Nextras\Orm\Repository\Repository;

abstract class AbstractRepository extends Repository
{

	use TRepositoryQueryable;

}
