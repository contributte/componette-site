<?php

namespace App\Modules\Front\Controls\RssFeed;

use App\Model\ORM\Addon\Addon;
use DateTimeZone;
use Nette\Application\UI\Control;
use Nette\Application\UI\Link;
use Nette\Utils\DateTime;

final class RssFeed extends Control
{

    /** @var Addon[] */
    private $addons;

    /** @var string */
    private $title;

    /** @var string|Link */
    private $link;

    /** @var string */
    private $description;

    /** @var string|int|DateTime */
    private $time;

    /**
     * @param Addon[] $addons
     */
    public function __construct(array $addons)
    {
        parent::__construct();
        $this->addons = $addons;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string|Link $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $items = [];
        foreach ($this->addons as $addon) {
            $items[] = (object) [
                'guid' => "$addon->id@componette.com",
                'title' => "[$addon->type] $addon->fullname",
                'link' => $this->presenter->link('//:Front:Addon:detail', ['slug' => $addon->id, 'utm_source' => 'rss', 'utm_medium' => 'rss', 'utm_campaign' => 'rss']),
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
        $this->template->title = $this->title;
        $this->template->link = (string) $this->link;
        $this->template->description = $this->description;
        $this->template->time = DateTime::from($this->time)->setTimezone(new DateTimeZone('UTC'));
        $this->template->items = $this->getItems();

        $this->template->setFile(__DIR__ . '/templates/rss.latte');
        $this->template->render();
    }

}
