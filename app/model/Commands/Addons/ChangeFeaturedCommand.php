<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ChangeFeaturedCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:change-featured';

	private AddonRepository $repository;

	private EntityManagerInterface $em;

	public function __construct(AddonRepository $repository, EntityManagerInterface $em)
	{
		parent::__construct();
		$this->repository = $repository;
		$this->em = $em;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$next = $this->repository->findOneBy(['featuredAt' => null]);
		if (!$next) {
			/** @var Addon|null $next */
			$next = $this->repository
				->createQueryBuilder('a')
				->orderBy('a.featuredAt', 'ASC')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();
		}

		if ($next === null) {
			$output->writeln('No addon found to feature.');
			return 1;
		}

		$next->setFeaturedAt(new DateTimeImmutable());
		$this->em->flush();
		$output->writeln(sprintf('Addon ID %d is now featured.', $next->getId()));
		return 0;
	}

}
