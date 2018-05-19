<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use Contributte\Nextras\Orm\QueryObject\QueryObject as NQueryObject;

abstract class QueryObject extends NQueryObject
{

	/** @var int */
	protected $limit;

	/** @var int */
	protected $offset;

	public function setLimit(int $limit): void
	{
		$this->limit = $limit;
	}

	public function setOffset(int $offset): void
	{
		$this->offset = $offset;
	}

}
