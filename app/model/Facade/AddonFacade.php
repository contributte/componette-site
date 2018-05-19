<?php declare(strict_types = 1);

namespace App\Model\Facade;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;

final class AddonFacade
{

	/** @var AddonRepository */
	private $addonRepository;

	public function __construct(AddonRepository $addonRepository)
	{
		$this->addonRepository = $addonRepository;
	}

	public function getById(int $id): ?Addon
	{
		return $this->addonRepository->getById($id);
	}

}
