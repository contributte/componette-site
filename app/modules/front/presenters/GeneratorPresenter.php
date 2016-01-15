<?php

namespace App\Modules\Front;

use App\Modules\Front\Controls\Sitemap\ISitemapFactory;
use App\Modules\Front\Controls\Sitemap\Sitemap;

final class GeneratorPresenter extends BasePresenter
{

    /** @var ISitemapFactory @inject */
    public $sitemapFactory;

    /**
     * @return Sitemap
     */
    protected function createComponentSitemap()
    {
        return $this->sitemapFactory->create();
    }

}
