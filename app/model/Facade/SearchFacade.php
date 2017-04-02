<?php

namespace App\Model\Facade;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\TagRepository;
use App\Model\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class SearchFacade
{

	/** @var AddonRepository */
	private $addonRepository;

	/** @var TagRepository */
	private $tagRepository;

	/** @var Search */
	private $search;

	/**
	 * @param AddonRepository $addonRepository
	 * @param TagRepository $tagRepository
	 * @param Search $search
	 */
	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository, Search $search)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
		$this->search = $search;
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findAll()
	{
		$collection = $this->addonRepository->findActive();
		$collection = $this->formatLimit($collection);

		return $collection;
	}


}
