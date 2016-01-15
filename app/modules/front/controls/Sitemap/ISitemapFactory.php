<?php

namespace App\Modules\Front\Controls\Sitemap;

interface ISitemapFactory
{

    /**
     * @return Sitemap
     */
    public function create();

}
