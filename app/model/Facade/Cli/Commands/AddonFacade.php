<?php declare(strict_types = 1);

namespace App\Model\Facade\Cli\Commands;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\EntityModel;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;
use Tracy\Debugger;

final class AddonFacade
{

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		$this->em = $em;
	}

	/**
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

		if ($input->getOption('rest') === true) {
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

	public function persist(AbstractEntity $entity): AbstractEntity
	{
		try {
			return $this->em->persist($entity);
		} catch (Throwable $e) {
			Debugger::log($e);
			throw $e;
		}
	}

	public function flush(): void
	{
		try {
			$this->em->flush();
		} catch (Throwable $e) {
			Debugger::log($e);
			throw $e;
		}
	}

}
