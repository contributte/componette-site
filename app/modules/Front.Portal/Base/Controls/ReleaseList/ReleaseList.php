<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\ReleaseList;

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\Database\Query\LatestReleaseIdsQuery;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use Wavevision\NetteWebpack\InjectNetteWebpack;

class ReleaseList extends BaseControl
{

	use InjectNetteWebpack;
	use SvgComponent;

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		$this->em = $em;
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function render(): void
	{
		$this->template->icon = $this->netteWebpack->getUrl('merge.svg');
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
