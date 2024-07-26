<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\ReleaseList;

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\Query\LatestReleaseIdsQuery;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;

class ReleaseList extends BaseControl
{

	use SvgComponent;

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		$this->em = $em;
	}

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
