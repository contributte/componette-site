<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use Contributte\Nextras\Orm\QueryObject\QueryObject as NQueryObject;

abstract class QueryObject extends NQueryObject
{

	/** @var int */
	protected $limit;

	/** @var int */
	protected $offset;

	/**
	 * @return static
	 */
	public function setLimit(int $limit): self
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * @return static
	 */
	public function setOffset(int $offset): self
	{
		$this->offset = $offset;

		return $this;
	}

}
