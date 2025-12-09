<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Tags;

use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\ORM\Tag\TagRepository;
use Nette\SmartObject;

class Tags
{

	use SmartObject;

	private TagRepository $tagRepository;

	public function __construct(TagRepository $tagRepository)
	{
		$this->tagRepository = $tagRepository;
	}

	/**
	 * @return Tag[]
	 */
	public function get(): array
	{
		$items = $this->tagRepository->findAll();
		usort($items, function ($a, $b) {
			return $a->getAddons()->count() <=> $b->getAddons()->count();
		});
		return $items;
	}

}
