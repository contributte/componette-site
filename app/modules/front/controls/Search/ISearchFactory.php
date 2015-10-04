<?php

namespace App\Modules\Front\Controls\Search;

interface ISearchFactory
{

    /**
     * @return Search
     */
    function create();
}