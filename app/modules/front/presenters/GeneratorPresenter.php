<?php

namespace App\Modules\Front;

use App\Modules\Front\Controls\RssFeed\IRssFeedFactory;
use App\Modules\Front\Controls\RssFeed\RssFeed;
use App\Modules\Front\Controls\Sitemap\ISitemapFactory;
use App\Modules\Front\Controls\Sitemap\Sitemap;

final class GeneratorPresenter extends BasePresenter
{

    /** @var IRssFeedFactory @inject */
    public $rssFeedFactory;

    /** @var ISitemapFactory @inject */
    public $sitemapFactory;

    /**
     * @return RssFeed
     */
    protected function createComponentRssFeed()
    {
        return $this->rssFeedFactory->create();
    }

    /**
     * @return Sitemap
     */
    protected function createComponentSitemap()
    {
        return $this->sitemapFactory->create();
    }

}
