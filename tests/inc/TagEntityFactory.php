<?php declare(strict_types = 1);

namespace AppTests\Inc;

use App\Model\Database\ORM\Tag\Tag;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityFactory;


class TagEntityFactory extends EntityFactory
{
	public function createTag(array $data): Tag
	{
		$tag = new Tag();
		$tag->name = $this->counter(Tag::class, 'tag');
		$tag->priority = 3;
		$tag->highlighted = false;

		return $tag;
	}
}
