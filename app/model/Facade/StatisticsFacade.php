<?php declare(strict_types = 1);

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

	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
	}

	public function countAddons(): int
	{
		return $this->addonRepository
			->findAll()
			->countStored();
	}

	public function countQueued(): int
	{
		return $this->addonRepository
			->findBy(['state' => Addon::STATE_QUEUED])
			->countStored();
	}

	public function countOwners(): int
	{
		$collection = $this->addonRepository
			->findAll()
			->fetchPairs('author');

		return count($collection);
	}

	public function countTags(): int
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
