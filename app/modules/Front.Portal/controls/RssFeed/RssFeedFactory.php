<?php

namespace App\Modules\Front\Portal\Controls\RssFeed;

use App\Model\Facade\AddonFacade;

final class RssFeedFactory
{

    /** @var AddonFacade */
    private $facade;

    /** @var IRssFeedFactory */
    private $rssFeedFactory;

    /**
     * @param AddonFacade $facade
     * @param IRssFeedFactory $rssFeedFactory
     */
    public function __construct(AddonFacade $facade, IRssFeedFactory $rssFeedFactory)
    {
        $this->facade = $facade;
        $this->rssFeedFactory = $rssFeedFactory;
    }

    /**
     * @return RssFeed
     */
    public function createNewest()
    {
        $control = $this->rssFeedFactory->create($this->facade->findNewest(25)->fetchAll());

        $control->setTitle('Componette - new addons');
        $control->setDescription('List of new addons as added by users on Componette.');

        return $control;
    }

}
