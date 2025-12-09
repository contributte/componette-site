<?php declare(strict_types = 1);

namespace App\Modules\Front\Addon\Controls\FeaturedAddon;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\AddonList\Avatar\AvatarComponent;
use App\Modules\Front\Base\Controls\AddonList\Description\DescriptionComponent;
use App\Modules\Front\Base\Controls\AddonList\Name\NameComponent;
use App\Modules\Front\Base\Controls\AddonList\Statistics\StatisticsComponent;

class Control extends BaseControl
{

	use AvatarComponent;
	use DescriptionComponent;
	use NameComponent;
	use StatisticsComponent;

	/**
	 * @var AddonRepository
	 * @inject
	 */
	public AddonRepository $repository;

	public function render(): void
	{
		if ($addon = $this->find()) {
			$this->template
				->setParameters(
					[
						'addon' => $addon,
					]
				)->render(__DIR__ . '/templates/default.latte');
		}
	}

	private function find(): ?Addon
	{
		/** @var Addon|null $addon */
		$addon = $this->repository
			->createQueryBuilder('a')
			->orderBy('a.featuredAt', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
		return $addon;
	}

}
