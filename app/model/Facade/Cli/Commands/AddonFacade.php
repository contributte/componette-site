<?php declare(strict_types = 1);

namespace App\Model\Facade\Cli\Commands;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;
use Tracy\Debugger;

final class AddonFacade
{

	private EntityManagerInterface $em;

	private AddonRepository $repository;

	public function __construct(EntityManagerInterface $em, AddonRepository $repository)
	{
		$this->em = $em;
		$this->repository = $repository;
	}

	/**
	 * @return Addon[]
	 */
	public function find(InputInterface $input): array
	{
		if (!$input->getArgument('type')) {
			throw new InvalidArgumentException('Argument type is required');
		}

		$criteria = ['state' => Addon::STATE_ACTIVE];

		if ($input->getOption('rest') === true) {
			$criteria = ['state' => Addon::STATE_QUEUED];
		}

		$type = $input->getArgument('type');
		switch ($type) {
			case 'composer':
				$criteria['type'] = Addon::TYPE_COMPOSER;
				break;

			case 'bower':
				$criteria['type'] = Addon::TYPE_BOWER;
				break;

			case 'unknown':
				$criteria['type'] = Addon::TYPE_UNKNOWN;
				break;

			case 'all':
				// Do nothing, just use all
				break;

			default:
				throw new InvalidArgumentException(sprintf('Unsupported type "%s"', is_array($type) ? print_r($type, true) : (string) $type));
		}

		return $this->repository->findBy($criteria);
	}

	public function persist(AbstractEntity $entity): void
	{
		try {
			$this->em->persist($entity);
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
