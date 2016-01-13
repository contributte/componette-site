<?php

namespace App\Modules\Front\Controls\Statistics;

interface IStatisticsFactory
{

    /**
     * @return Statistics
     */
    public function create();

}
