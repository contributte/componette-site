<?php

namespace App\Model\Facade\Cli\Commands;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;

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
	 * @param InputInterface $input
	 * @return ICollection|Addon[]
	 */
	public function find(InputInterface $input)
	{
		if (!$input->getArgument('type')) {
			throw new InvalidArgumentException('Argument type is required');
		}

		/** @var ICollection|Addon[] $addons */
		$addons = $this->addonRepository->findActive();

		if ($input->getOption('rest') == TRUE) {
			$addons = $this->addonRepository->findBy(['state' => Addon::STATE_QUEUED]);
		}

		switch ($input->getArgument('type')) {
			case 'composer':
				// Use only composer types
				$addons = $addons->findBy(['type' => Addon::TYPE_COMPOSER]);
				break;
			case 'bower':
				// Use only bower types
				$addons = $addons->findBy(['type' => Addon::TYPE_BOWER]);
				break;
			case 'unknown':
				// Use only uknown types
				$addons = $addons->findBy(['type' => Addon::TYPE_UNKNOWN]);
				break;
			case 'all':
				// Do nothing, just use all
				break;
			default:
				throw new InvalidArgumentException(sprintf('Unsupported type "%s"', $input->getArgument('type')));
		}

		return $addons;
	}

	/**
	 * @param Addon $addon
	 * @return Addon
	 */
	public function save(Addon $addon)
	{
		return $this->addonRepository->persistAndFlush($addon);
	}

}
