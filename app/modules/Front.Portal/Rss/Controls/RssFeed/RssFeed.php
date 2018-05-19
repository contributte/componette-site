<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use DateTimeZone;
use Nette\Application\UI\Link;
use Nette\Utils\DateTime;

final class RssFeed extends BaseControl
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

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @param string|Link $link
	 */
	public function setLink($link): void
	{
		$this->link = $link;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @param mixed $time
	 */
	public function setTime($time): void
	{
		$this->time = $time;
	}

	/**
	 * @return mixed[]
	 */
	public function getItems(): array
	{
		$items = [];
		foreach ($this->addons as $addon) {
			$items[] = $item = (object) [
				'guid' => sprintf('%s@componette.com', $addon->id),
				'link' => $this->presenter->link('//:Front:Portal:Addon:detail', ['slug' => $addon->id, 'utm_source' => 'rss', 'utm_medium' => 'rss', 'utm_campaign' => 'rss']),
				'time' => $addon->createdAt->setTimezone(new DateTimeZone('UTC')),
				'author' => sprintf('noreply@componette.com (%s)', $addon->author),
				'content' => $addon->github && $addon->github->contentHtml,
			];

			if ($addon->github && $addon->github->description) {
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
	 */
	public function render(): void
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
