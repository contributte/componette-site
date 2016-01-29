<?php

namespace App\Modules\Front\Controls\RssFeed;

use App\Model\ORM\Addon\Addon;
use DateTimeZone;
use Nette\Application\UI\Control;
use Nette\Utils\DateTime;

class RssFeed extends Control
{

    /** @var Addon[] */
    private $addons;

    /**
     * @param Addon[] $addons
     */
    public function __construct(array $addons)
    {
        parent::__construct();
        $this->addons = $addons;
    }

    /**
     * @return array
     */
    private function getItems()
    {
        $items = [];
        foreach ($this->addons as $addon) {
            $items[] = (object)[
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
