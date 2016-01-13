<?php

namespace App\Modules\Front\Controls\Statistics;

use App\Model\Facade\StatisticsFacade;
use Nette\Application\UI\Control;

class Statistics extends Control
{

    /** @var StatisticsFacade */
    private $facade;

    /**
     * @param StatisticsFacade $facade
     */
    public function __construct(StatisticsFacade $facade)
    {
        parent::__construct();
        $this->facade = $facade;
    }

    /**
     * RENDER ******************************************************************
     */

    public function renderFooter()
    {
        $this->template->addons = $this->facade->countAddons();
        $this->template->queued = $this->facade->countQueued();
        $this->template->owners = $this->facade->countOwners();
        $this->template->tags = $this->facade->countTags();

        $this->template->popular = $this->facade->findMostPopular();
        $this->template->newest = $this->facade->findNewest();

        $this->template->setFile(__DIR__ . '/templates/footer.latte');
        $this->template->render();
    }

}
