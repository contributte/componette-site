<?php

namespace App\Modules\Front\Portal\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
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
	public function __construct($addons)
	{
		parent::__construct();
		$this->addons = $addons;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @param string|Link $link
	 * @return void
	 */
	public function setLink($link)
	{
		$this->link = $link;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param mixed $time
	 * @return void
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
			$items[] = $item = (object) [
				'guid' => sprintf('%s@componette.com', $addon->id),
				'link' => $this->presenter->link('//:Front:Portal:Addon:detail', ['slug' => $addon->id, 'utm_source' => 'rss', 'utm_medium' => 'rss', 'utm_campaign' => 'rss']),
				'time' => $addon->createdAt->setTimezone(new DateTimeZone('UTC')),
				'author' => sprintf('noreply@componette.com (%s)', $addon->author),
				'content' => $addon->github->contentHtml,
			];

			if ($addon->github->description) {
				$item->title = sprintf('%s - %s', $addon->fullname, $addon->github->description);
			} else {
				$item->title = sprintf('%s', $addon->fullname);
			}
		}

		return $items;
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 *
	 * @return void
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
