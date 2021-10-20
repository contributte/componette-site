<?php declare(strict_types = 1);

namespace App\Modules\Front\Addon\Controls\FeaturedAddon;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\AddonList\Avatar\AvatarComponent;
use App\Modules\Front\Base\Controls\AddonList\Description\DescriptionComponent;
use App\Modules\Front\Base\Controls\AddonList\Name\NameComponent;
use App\Modules\Front\Base\Controls\AddonList\Statistics\StatisticsComponent;
use Nextras\Orm\Collection\ICollection;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\NetteWebpack\InjectNetteWebpack;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BaseControl
{

	use AvatarComponent;
	use DescriptionComponent;
	use InjectNetteWebpack;
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
						'icon' => $this->netteWebpack->getUrl($this->netteWebpack->getAsset('trophy.svg')),
					]
				)->render(__DIR__ . '/templates/default.latte');
		}
	}

	private function find(): ?Addon
	{
		/** @var Addon|null $addon */
		$addon = $this->repository
			->findBy([])
			->limitBy(1)
			->orderBy('featuredAt', ICollection::DESC)
			->fetch();
		return $addon;
	}

}
