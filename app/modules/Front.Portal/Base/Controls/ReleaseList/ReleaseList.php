<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\ReleaseList;

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\Query\LatestReleaseIdsQuery;
use App\Model\UI\BaseControl;

class ReleaseList extends BaseControl
{

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function render(): void
	{
		$ids = $this->em->getRepositoryForEntity(GithubRelease::class)
			->fetchResult(new LatestReleaseIdsQuery())
			->fetchPairs(null, 'id');

		$this->template->releases = $this->em->getRepositoryForEntity(GithubRelease::class)
			->findById($ids)
			->orderBy('publishedAt', 'DESC')
			->limitBy(15);

		$this->template->setFile(__DIR__ . '/templates/list.latte');
		$this->template->render();
	}

}
