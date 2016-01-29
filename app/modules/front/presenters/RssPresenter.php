<?php

namespace App\Modules\Front;

use App\Modules\Front\Controls\RssFeed\RssFeed;
use App\Modules\Front\Controls\RssFeed\RssFeedFactory;

final class RssPresenter extends BasePresenter
{

    /** @var RssFeedFactory @inject */
    public $rssFeedFactory;

    /**
     * @return RssFeed
     */
    protected function createComponentNewest()
    {
        return $this->rssFeedFactory->createNewest();
    }

}
