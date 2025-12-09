<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use Nettrine\Extra\Entity\AbstractEntity as NettrineAbstractEntity;
use Nettrine\Extra\Entity\TGeneratedId;

abstract class AbstractEntity extends NettrineAbstractEntity
{

	use TGeneratedId;

}
