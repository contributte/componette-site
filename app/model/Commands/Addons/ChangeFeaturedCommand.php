<?php declare(strict_types = 1);

namespace App\Model\Commands\Addons;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\EntityModel;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ChangeFeaturedCommand extends BaseCommand
{

	/** @var string */
	protected static $defaultName = 'addons:change-featured';

	private AddonRepository $repository;

	private EntityModel $em;

	public function __construct(AddonRepository $repository, EntityModel $em)
	{
		parent::__construct();
		$this->repository = $repository;
		$this->em = $em;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$next = $this->repository->getBy(['featuredAt' => null]);
		if (!$next) {
			/** @var Addon $next */
			$next = $this->repository
				->findBy([])
				->limitBy(1)
				->orderBy('featuredAt', ICollection::ASC)
				->fetch();
		}
		$next->featuredAt = new DateTimeImmutable();
		$this->em->persistAndFlush($next);
		$output->writeln(sprintf('Addon ID %d is now featured.', $next->id));
		return 0;
	}

}
