<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\ReleaseList;

use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;

class ReleaseList extends BaseControl
{

	use SvgComponent;

	private GithubReleaseRepository $releaseRepository;

	public function __construct(GithubReleaseRepository $releaseRepository)
	{
		$this->releaseRepository = $releaseRepository;
	}

	public function render(): void
	{
		// Get latest release IDs grouped by github_id
		$qb = $this->releaseRepository->createQueryBuilder('gr')
			->select('MAX(gr.id) as id')
			->groupBy('gr.github')
			->orderBy('MAX(gr.publishedAt)', 'DESC')
			->setMaxResults(15);

		$ids = array_column($qb->getQuery()->getArrayResult(), 'id');

		// Fetch the actual releases
		if (!empty($ids)) {
			$this->template->releases = $this->releaseRepository->createQueryBuilder('r')
				->where('r.id IN (:ids)')
				->setParameter('ids', $ids)
				->orderBy('r.publishedAt', 'DESC')
				->getQuery()
				->getResult();
		} else {
			$this->template->releases = [];
		}

		$this->template->setFile(__DIR__ . '/templates/list.latte');
		$this->template->render();
	}

}
