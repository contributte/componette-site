<?php

namespace App\Modules\Front\Portal\Controls\Search;

interface ISearchFactory
{

    /**
     * @return Search
     */
    public function create();

}
