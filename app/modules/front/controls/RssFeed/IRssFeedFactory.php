<?php

namespace App\Modules\Front\Controls\RssFeed;

interface IRssFeedFactory
{

    /**
     * @return RssFeed
     */
    public function create();

}
