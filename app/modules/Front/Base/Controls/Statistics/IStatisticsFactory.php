<?php

namespace App\Modules\Front\Base\Controls\Statistics;

interface IStatisticsFactory
{

	/**
	 * @return Statistics
	 */
	public function create();

}
