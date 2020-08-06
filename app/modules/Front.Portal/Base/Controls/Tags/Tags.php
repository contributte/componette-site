<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Tags;

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use Nette\SmartObject;
use Nextras\Orm\Entity\IEntity;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateInject=true)
 */
class Tags
{

	use SmartObject;

	private EntityModel $em;

	public function __construct(EntityModel $em)
	{
		$this->em = $em;
	}

	/**
	 * @return array<IEntity>
	 */
	public function get(): array
	{
		$items = $this->em->getRepositoryForEntity(Tag::class)->findAll()->fetchAll();
		usort($items, function ($a, $b) {
			return $a->addons->countStored() < $b->addons->countStored();
		});
		return $items;
	}

}
