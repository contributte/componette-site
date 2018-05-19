<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Statistics;

interface IStatisticsFactory
{

	public function create(): Statistics;

}
