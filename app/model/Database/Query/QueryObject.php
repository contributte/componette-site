<?php

namespace App\Model\Database\Query;

use Minetro\Nextras\Orm\QueryObject\QueryObject as NQueryObject;

abstract class QueryObject extends NQueryObject
{

	/** @var int */
	protected $limit;

	/** @var int */
	protected $offset;

	/**
	 * @param int $limit
	 * @return void
	 */
	public function setLimit(int $limit)
	{
		$this->limit = $limit;
	}

	/**
	 * @param int $offset
	 * @return void
	 */
	public function setOffset(int $offset)
	{
		$this->offset = $offset;
	}

}
