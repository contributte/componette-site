<?php

namespace App\Modules\Front\Portal\Controls\Sitemap;

interface ISitemapFactory
{

    /**
     * @return Sitemap
     */
    public function create();

}
