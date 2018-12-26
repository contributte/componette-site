<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\ReleaseList;

interface IReleaseListFactory
{

	public function create(): ReleaseList;

}
