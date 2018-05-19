<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Status;

interface IStatusFactory
{

	public function create(): Status;

}
