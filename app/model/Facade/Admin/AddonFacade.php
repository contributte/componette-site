<?php

namespace App\Model\Facade\Admin;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\ORM\Tag\TagRepository;
use Nextras\Orm\Collection\ICollection;

final class AddonFacade
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
	 * @param int $id
	 * @return Addon|NULL
	 */
	public function getById($id)
	{
		return $this->addonRepository->getById($id);
	}

	/**
	 * @param Addon $addon
	 * @return void
	 */
	public function update(Addon $addon)
	{
		$this->addonRepository->persistAndFlush($addon);
	}

	/**
	 * @return ICollection|Addon[]
	 */
	public function findAll()
	{
		return $this->addonRepository
			->findAll();
	}

	/**
	 * @return ICollection|Tag[]
	 */
	public function findTags()
	{
		return $this->tagRepository
			->findAll();
	}

}
