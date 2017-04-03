<?php

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Exceptions\Logical\InvalidStateException;
use Nette\Utils\Strings;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class OpenSearchQuery extends QueryObject
{

	/** @var string */
	private $token;

	/**
	 * @param string $query
	 * @return void
	 */
	public function byQuery(string $query)
	{
		$query = Strings::replace($query, '#[.\-]#', ' ');
		//$tokens = explode(' ', $query);

		$this->token = $query;
	}

	/**
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function doQuery(QueryBuilder $builder)
	{
		if (!$this->token) {
			throw new InvalidStateException('Provide search query');
		}

		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->addOrderBy('[a.rating] DESC')
			->addOrderBy('[a.created_at] DESC');

		$qb->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->andWhere('[a.author] LIKE %s OR [a.name] LIKE %s', '%' . $this->token . '%', '%' . $this->token . '%')
			->groupBy('[a.id]');

		return $qb;
	}

}
