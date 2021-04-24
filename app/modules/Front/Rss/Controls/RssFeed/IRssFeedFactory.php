<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Orm\Collection\ICollection;

interface IRssFeedFactory
{

	/**
	 * @param ICollection|Addon[] $addons
	 */
	public function create($addons): RssFeed;

}
