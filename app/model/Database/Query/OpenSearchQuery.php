<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Exceptions\Logical\InvalidStateException;
use Nette\Utils\Strings;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class OpenSearchQuery extends QueryObject
{

	/** @var string */
	private $token;

	public function byQuery(string $query): void
	{
		$query = Strings::replace($query, '#[.\-]#', ' ');
		//$tokens = explode(' ', $query);

		$this->token = $query;
	}

	public function doQuery(QueryBuilder $builder): QueryBuilder
	{
		if (!$this->token) {
			throw new InvalidStateException('Provide search query');
		}

		$qb = $builder->select('g.*, a.*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->addOrderBy('[a.rating] DESC')
			->addOrderBy('[a.created_at] DESC');

		$qb->rightJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->andWhere('[a.author] LIKE %s OR [a.name] LIKE %s', '%' . $this->token . '%', '%' . $this->token . '%')
			->groupBy('[a.id]');

		return $qb;
	}

}
