<?php

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

final class TagRepository extends AbstractRepository
{

	/**
	 * @return array
	 */
	public static function getEntityClassNames()
	{
		return [Tag::class];
	}

	/**
	 * @return array
	 */
	public function fetchPairs()
	{
		return $this->findAll()->orderBy('name')->fetchPairs('id', 'name');
	}

	/**
	 * @return ICollection
	 */
	public function findWithHighPriority()
	{
		return $this->findBy(['priority>' => 0])->orderBy('priority', 'DESC');
	}

	/**
	 * @return ICollection
	 */
	public function findWithLowPriority()
	{
		return $this->findBy(['priority' => 0])->orderBy('name', 'ASC');
	}

}
