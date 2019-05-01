<?php declare(strict_types = 1);

namespace App\Model\Commands\Twitter;

use App\Model\Commands\BaseCommand;
use App\Model\Database\ORM\EntityModel;
use App\Model\WebServices\ReleaseButler\ReleaseButlerService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeReleasesCommand extends BaseCommand
{

	/** @var EntityModel */
	private $em;

	/** @var ReleaseButlerService */
	private $releaseButlerService;

	public function __construct(
		EntityModel $em,
		ReleaseButlerService $releaseButlerService
	)
	{
		parent::__construct();
		$this->em = $em;
		$this->releaseButlerService = $releaseButlerService;
	}

	/**
	 * Configure command
	 */
	protected function configure(): void
	{
		$this
			->setName('twitter:changelog')
			->setDescription('Create changelog image and post it on Twitter');
	}

	protected function execute(InputInterface $input, OutputInterface $output): void
	{

	}

}
