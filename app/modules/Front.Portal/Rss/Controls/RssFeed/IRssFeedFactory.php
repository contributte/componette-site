<?php

namespace App\Modules\Front\Portal\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;

interface IRssFeedFactory
{

	/**
	 * @param Addon[] $addons
	 * @return RssFeed
	 */
	public function create($addons);

}
