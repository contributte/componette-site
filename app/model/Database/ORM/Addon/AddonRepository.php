<?php

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @property-read AddonMapper $mapper
 *
 * @method Addon[] findAll()
 */
final class AddonRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [Addon::class];
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findActive()
	{
		return $this->findBy(['state' => Addon::STATE_ACTIVE]);
	}

	/**
	 * @param string $orderBy
	 * @return ICollection|Addon[]
	 */
	public function findOrdered($orderBy)
	{
		$result = $this->mapper->findOrdered($orderBy);

		return $this->mapper->toCollection($result);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findComposers()
	{
		return $this->findActive()->findBy(['type' => Addon::TYPE_COMPOSER]);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findBowers()
	{
		return $this->findActive()->findBy(['type' => Addon::TYPE_BOWER]);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findUnknowns()
	{
		return $this->findActive()->findBy(['type' => Addon::TYPE_UNKNOWN]);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findUntypes()
	{
		return $this->findActive()->findBy(['type' => Addon::TYPE_UNTYPE]);
	}

}
