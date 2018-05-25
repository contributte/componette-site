<?php declare(strict_types = 1);

namespace AppTests\Inc;

use App\Model\Database\ORM\Addon\Addon;
use Mangoweb\Tester\NextrasOrmEntityGenerator\EntityFactory;


class AddonEntityFactory extends EntityFactory
{
	public function createAddon(array $data): Addon
	{
		$addon = new Addon();
		$addon->type = Addon::TYPE_COMPOSER;
		$addon->state = Addon::STATE_ACTIVE;
		$addon->author = 'mangoweb';
		$addon->name = 'presenter-tester';

		return $addon;
	}
}
