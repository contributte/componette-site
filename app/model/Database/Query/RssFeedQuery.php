<?php

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Exceptions\Logical\InvalidStateException;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class RssFeedQuery extends QueryObject
{

	// Types
	const TYPE_LATEST = 1;
	const TYPE_AUTHOR = 2;

	// Limits
	const DEFAULT_LIMIT = 25;

	/** @var int */
	private $type;

	/** @var string */
	private $author;

	/**
	 * @return void
	 */
	public function byLatest(): void
	{
		$this->type = self::TYPE_LATEST;
	}

	/**
	 * @param string $author
	 * @return void
	 */
	public function byAuthor(string $author): void
	{
		$this->type = self::TYPE_AUTHOR;
		$this->author = $author;
	}

	/**
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function doQuery(QueryBuilder $builder)
	{
		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

		if ($this->type === self::TYPE_LATEST) {
			$qb->orderBy('[a.created_at] DESC');
		} elseif ($this->type === self::TYPE_AUTHOR) {
			$qb->andWhere('[a.author] = %s', $this->author);
		} else {
			throw new InvalidStateException('Unknown type');
		}

		if (!$this->limit) {
			$qb->limitBy(self::DEFAULT_LIMIT);
		}

		return $qb;
	}

}
