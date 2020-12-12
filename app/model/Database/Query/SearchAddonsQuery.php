<?php declare(strict_types = 1);

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

	/** @var string[] */
	private $tokens = [];

	public function byAuthor(string $author): void
	{
		$this->author = $author;
	}

	public function byTag(string $tag): void
	{
		$this->tag = $tag;
	}

	public function byQuery(string $query): void
	{
		$query = Strings::replace($query, '#[.\-]#', ' ');
		$tokens = explode(' ', $query);

		$this->tokens = $tokens;
	}

	public function doQuery(QueryBuilder $builder): QueryBuilder
	{
		$qb = $builder->select('a.*')
			->from('[addon]', 'a')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE)
			->addOrderBy('[a.rating] DESC')
			->addOrderBy('[a.created_at] DESC');

		if ($this->author) {
			$qb->andWhere('[a.author] = %s', $this->author);
		}

		if ($this->tag) {
			$qb->rightJoin('a', '[addon_x_tag]', 'axt', '[axt.addon_id] = [a.id]')
				->rightJoin('t', '[tag]', 't', '[t.id] = [axt.tag_id]')
				->andWhere('[t.name] = %s', $this->tag)
				->groupBy('[a.id]');
		}

		if ($this->tokens) {
			$qb->rightJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
				->rightJoin('a', '[composer]', 'c', '[c.addon_id] = [a.id]');

			foreach ($this->tokens as $token) {
				$builder->andWhere(
					'[a.author] LIKE %s '
					. 'OR [a.name] LIKE %s '
					. 'OR [g.description] LIKE %s '
					. 'OR [c.name] LIKE %s',
					'%' . $token . '%',
					'%' . $token . '%',
					'%' . $token . '%',
					'%' . $token . '%'
				);
			}

			$qb->groupBy('[a.id]');
		}

		return $qb;
	}

}
