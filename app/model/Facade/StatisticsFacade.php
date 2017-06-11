<?php

namespace App\Model\Facade;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\TagRepository;
use Nextras\Orm\Collection\ICollection;

final class StatisticsFacade
{

	/** @var AddonRepository */
	private $addonRepository;

	/** @var TagRepository */
	private $tagRepository;

	/**
	 * @param AddonRepository $addonRepository
	 * @param TagRepository $tagRepository
	 */
	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
	}

	/**
	 * @return int
	 */
	public function countAddons()
	{
		return $this->addonRepository
			->findAll()
			->countStored();
	}

	/**
	 * @return int
	 */
	public function countQueued()
	{
		return $this->addonRepository
			->findBy(['state' => Addon::STATE_QUEUED])
			->countStored();
	}

	/**
	 * @return int
	 */
	public function countOwners()
	{
		$collection = $this->addonRepository
			->findAll()
			->fetchPairs('author');

		return count($collection);
	}

	/**
	 * @return int
	 */
	public function countTags()
	{
		return $this->tagRepository
			->findAll()
			->countStored();
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findNewest()
	{
		return $this->addonRepository
			->findAll()
			->orderBy('createdAt', 'DESC')
			->limitBy(5);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findMostPopular()
	{
		return $this->addonRepository
			->findAll()
			->orderBy('popularity DESC')
			->limitBy(5);
	}

}
