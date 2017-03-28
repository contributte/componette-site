<?php

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractMapper;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class AddonMapper extends AbstractMapper
{

	/**
	 * @param mixed $q
	 * @return QueryBuilder
	 */
	public function findByQuery($q)
	{
		$tokens = explode(' ', $q);

		$builder = $this->builder()
			->from('[addon]', 'a')
			->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->leftJoin('a', '[composer]', 'c', '[c.addon_id] = [a.id]')
			->leftJoin('a', '[bower]', 'b', '[b.addon_id] = [a.id]');

		foreach ($tokens as $token) {
			$builder->andWhere('[a.owner] LIKE %s', "%$token%")
				->orWhere('[a.name] LIKE %s', "%$token%")
				->orWhere('[g.description] LIKE %s', "%$token%")
				// Composer
				->orWhere('[c.name] LIKE %s', "%$token%")
				// Bower
				->orWhere('[b.name] LIKE %s', "%$token%");
		}

		$builder->groupBy('[a.id]')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

		return $builder;
	}

	/**
	 * @param mixed $q
	 * @return QueryBuilder
	 */
	public function findByOwnerOrName($q)
	{
		$builder = $this->builder()
			->from('[addon]', 'a')
			->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->orWhere('[a.owner] LIKE %s', "%$q%")
			->orWhere('[a.name] LIKE %s', "%$q%")
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

		return $builder;
	}

	/**
	 * @param string $orderBy
	 * @return QueryBuilder
	 */
	public function findOrdered($orderBy)
	{
		$builder = $this->builder();
		$builder->from('[addon]', 'a')
			->leftJoin('a', '[github]', 'g', '[g.addon_id] = [a.id]')
			->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

		$this->applyOrder($builder, $orderBy);

		return $builder;
	}

	/**
	 * @param QueryBuilder $builder
	 * @param string $orderBy
	 */
	public function applyOrder(QueryBuilder $builder, $orderBy)
	{
		switch ($orderBy) {
			case 'push':
				$builder->orderBy('[g.pushed_at] DESC');
				break;
			case 'newest':
				$builder->orderBy('[a.created_at] DESC');
				break;
			case 'popularity':
				$builder->orderBy('IFNULL([g.stars], 0) * 2 + IFNULL([g.watchers], 0) + IFNULL([g.forks], 0) DESC');
				break;
		}
	}

}
