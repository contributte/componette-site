<?php

namespace App\Modules\Front\Portal\Controls\Statistics;

interface IStatisticsFactory
{

    /**
     * @return Statistics
     */
    public function create();

}
