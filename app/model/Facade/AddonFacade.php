<?php

namespace App\Model\Facade;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;

final class AddonFacade
{

	/** @var AddonRepository */
	private $addonRepository;

	/**
	 * @param AddonRepository $addonRepository
	 */
	public function __construct(AddonRepository $addonRepository)
	{
		$this->addonRepository = $addonRepository;
	}

	/**
	 * @param int $id
	 * @return Addon|NULL
	 */
	public function getById($id)
	{
		return $this->addonRepository->getById($id);
	}

}
