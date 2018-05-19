<?php declare(strict_types = 1);

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

	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
	}

	public function getById(int $id): ?Addon
	{
		return $this->addonRepository->getById($id);
	}

	public function update(Addon $addon): void
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
