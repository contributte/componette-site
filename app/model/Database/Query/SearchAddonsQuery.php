<?php

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\Strings;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class SearchAddonsQuery extends QueryObject
{

	/** @var string */
	private $author;

	/** @var string */
	private $tag;

	/** @var array */
	private $tokens = [];

	/**
	 * @param string $author
	 * @return void
	 */
	public function byAuthor(string $author)
	{
		$this->author = $author;
	}

	/**
	 * @param string $tag
	 * @return void
	 */
	public function byTag(string $tag)
	{
		$this->tag = $tag;
	}

	/**
	 * @param string $query
	 * @return void
	 */
	public function byQuery(string $query)
	{
		$query = Strings::replace($query, '#[.\-]#', ' ');
		$tokens = explode(' ', $query);

		$this->tokens = $tokens;
	}

	/**
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function doQuery(QueryBuilder $builder)
	{
		$qb = $builder->select('*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->addOrderBy('[a.rating] DESC')
			->addOrderBy('[a.created_at] DESC');

		if ($this->author) {
			$qb->andWhere('[a.author] = %s', $this->author);
		}

		if ($this->tag) {
			$qb->leftJoin('a', '[addon_x_tag]', 'axt', '[axt.addon_id] = [a.id]')
				->leftJoin('t', '[tag]', 't', '[t.id] = [axt.tag_id]')
				->andWhere('[t.name] = %s', $this->tag)
				->groupBy('[a.id]');
		}

		if ($this->tokens) {
			$qb->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
				->leftJoin('a', '[composer]', 'c', '[c.addon_id] = [a.id]');

			foreach ($this->tokens as $token) {
				$builder->andWhere('[a.author] LIKE %s', "%$token%")
					->orWhere('[a.name] LIKE %s', "%$token%")
					->orWhere('[g.description] LIKE %s', "%$token%")
					// Composer
					->orWhere('[c.name] LIKE %s', "%$token%");
			}
			$qb->groupBy('[a.id]');
		}

		return $qb;
	}

}
