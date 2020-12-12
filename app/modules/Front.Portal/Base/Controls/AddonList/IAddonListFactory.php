<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use Nextras\Orm\Collection\ICollection;

interface IAddonListFactory
{

	/**
	 * @param ICollection<Addon> $addons
	 */
	public function create(ICollection $addons): AddonList;

}
