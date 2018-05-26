<?php declare(strict_types = 1);

namespace ApptTests\Inc;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\Tag\Tag;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityFactory;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityGenerator;

class AddonEntityFactory extends EntityFactory
{
	public function createAddon(array $data, EntityGenerator $entityGenerator)
	{
		$this->verifyData(['name', 'author', 'github', 'tags'], $data);

		$addon = new Addon();
		$addon->name = $data['name'] ?? 'Testx addon';
		$addon->author = $data['author'] ?? 'nextras';
		$addon->state = Addon::STATE_ACTIVE;
		$addon->type = Addon::TYPE_COMPOSER;
		$github = $entityGenerator->maybeCreate(Github::class, $data['github'] ?? [], ['addon' => $addon]);
		$addon->github = $github;

		$tags = $entityGenerator->createList(Tag::class, $data['tags'] ?? 0);
		$addon->tags->set($tags);

		return $addon;
	}

	public function createGithub(array $data, EntityGenerator $entityGenerator)
	{
		$this->verifyData(['stars', 'addon'], $data);
		$github = new Github();
		$github->addon = $entityGenerator->maybeCreate(Addon::class, $data['addon'] ?? []);
		$github->stars = $data['stars'] ?? NULL;
		return $github;
	}


	public function createTag(array $data)
	{
		$this->verifyData(['name'], $data);
		$tag = new Tag();
		$name = $data['name'] ?? $this->counter(Tag::class, 'Tag ');
		$tag->name = $name;
		$tag->priority = 10;
		$tag->highlighted = false;

		return $tag;
	}
}
