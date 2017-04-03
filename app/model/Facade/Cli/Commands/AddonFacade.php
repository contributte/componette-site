<?php

namespace App\Model\Facade\Cli\Commands;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\EntityModel;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Exception;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Tracy\Debugger;

final class AddonFacade
{

	/** @var EntityModel */
	private $em;

	/**
	 * @param EntityModel $em
	 */
	public function __construct(EntityModel $em)
	{
		$this->em = $em;
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

		/** @var AddonRepository $repository */
		$repository = $this->em->getRepositoryForEntity(Addon::class);

		/** @var ICollection|Addon[] $addons */
		$addons = $repository->findBy(['state' => Addon::STATE_ACTIVE]);

		if ($input->getOption('rest') == TRUE) {
			$addons = $repository->findBy(['state' => Addon::STATE_QUEUED]);
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
	 * @param AbstractEntity $entity
	 * @return AbstractEntity
	 */
	public function persist(AbstractEntity $entity)
	{
		try {
			return $this->em->persist($entity);
		} catch (Exception $e) {
			Debugger::log($e);
			throw $e;
		}
	}

	/**
	 * @return void
	 */
	public function flush()
	{
		try {
			$this->em->flush();
		} catch (Exception $e) {
			Debugger::log($e);
			throw $e;
		}
	}

}
