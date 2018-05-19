<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Addon\Controls\AddonDetail;

use App\Model\Database\ORM\Addon\Addon;

interface IAddonDetailFactory
{

	public function create(Addon $addon): AddonDetail;

}
