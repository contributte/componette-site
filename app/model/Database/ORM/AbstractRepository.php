<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * @template TEntityClass of object
 * @extends EntityRepository<TEntityClass>
 */
abstract class AbstractRepository extends EntityRepository
{

	/**
	 * @return TEntityClass
	 * @throws EntityNotFoundException
	 */
	public function fetch(int $id): object
	{
		$entity = $this->find($id);

		if ($entity === null) {
			throw EntityNotFoundException::fromClassNameAndIdentifier(
				$this->getEntityName(),
				[(string) $id]
			);
		}

		return $entity;
	}

	/**
	 * @param array<string, mixed> $criteria
	 * @param array<string, string>|null $orderBy
	 * @return TEntityClass
	 * @throws EntityNotFoundException
	 */
	public function fetchBy(array $criteria, ?array $orderBy = null): object
	{
		$entity = $this->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw EntityNotFoundException::fromClassNameAndIdentifier(
				$this->getEntityName(),
				array_map(fn ($v) => (string) $v, array_values($criteria))
			);
		}

		return $entity;
	}

}
