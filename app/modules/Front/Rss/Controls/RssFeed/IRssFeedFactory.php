<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;

interface IRssFeedFactory
{

	/**
	 * @param Addon[] $addons
	 */
	public function create(array $addons): RssFeed;

}
