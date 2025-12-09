<?php declare(strict_types = 1);

namespace App\Model\Facade;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\TagRepository;

final class StatisticsFacade
{

	private AddonRepository $addonRepository;

	private TagRepository $tagRepository;

	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
	}

	public function countAddons(): int
	{
		return $this->addonRepository->count([]);
	}

	public function countQueued(): int
	{
		return $this->addonRepository->count(['state' => Addon::STATE_QUEUED]);
	}

	public function countOwners(): int
	{
		$qb = $this->addonRepository->createQueryBuilder('a')
			->select('COUNT(DISTINCT a.author)')
			->getQuery();

		return (int) $qb->getSingleScalarResult();
	}

	public function countTags(): int
	{
		return $this->tagRepository->count([]);
	}

	/**
	 * @return Addon[]
	 */
	public function findNewest(): array
	{
		return $this->addonRepository->findBy([], ['createdAt' => 'DESC'], 5);
	}

	/**
	 * @return Addon[]
	 */
	public function findMostPopular(): array
	{
		return $this->addonRepository->createQueryBuilder('a')
			->orderBy('a.rating', 'DESC')
			->setMaxResults(5)
			->getQuery()
			->getResult();
	}

}
