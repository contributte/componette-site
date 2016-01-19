<?php

namespace App\Modules\Front\Controls\RssFeed;

use App\Model\Facade\AddonFacade;
use DateTimeZone;
use Nette\Application\UI\Control;
use Nette\Utils\DateTime;

class RssFeed extends Control
{

    /** @var AddonFacade */
    private $facade;

    /**
     * @param AddonFacade $facade
     */
    public function __construct(AddonFacade $facade)
    {
        parent::__construct();
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    private function getItems()
    {
        $items = [];
        foreach ($this->facade->findNewest(25) as $addon) {
            $items[] = (object) [
                'guid' => "$addon->id@componette.com",
                'title' => "[$addon->type] $addon->fullname",
                'link' => $this->presenter->link('//:Front:Addon:detail', ['slug' => $addon->id]),
                'time' => $addon->createdAt->setTimezone(new DateTimeZone('UTC')),
                'author' => "noreply@componette.com ($addon->owner)",
                'content' => $addon->github->content,
            ];
        }

        return $items;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->title = 'Componette - new addons';
        $this->template->link = $this->presenter->link('//:Front:Home:default');
        $this->template->description = 'List of new addons as added by users on Componette.';
        $this->template->time = (new DateTime)->setTimezone(new DateTimeZone('UTC'));

        $this->template->items = $this->getItems();
        $this->template->setFile(__DIR__ . '/templates/rss-feed.latte');
        $this->template->render();
    }

}
