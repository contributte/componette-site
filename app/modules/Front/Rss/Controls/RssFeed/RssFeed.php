<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use DateTimeZone;
use Nette\Application\UI\Link;
use Nette\Utils\DateTime;

final class RssFeed extends BaseControl
{

	/** @var Addon[] */
	private array $addons;

	private string $title;

	/** @var string|Link */
	private $link;

	private string $description;

	/** @var string|int|DateTime */
	private $time;

	/**
	 * @param Addon[] $addons
	 */
	public function __construct(array $addons)
	{
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
				'guid' => sprintf('%s@componette.org', $addon->getId()),
				'link' => $this->presenter->link('//:Front:Addon:detail', ['slug' => $addon->getId(), 'utm_source' => 'rss', 'utm_medium' => 'rss', 'utm_campaign' => 'rss']),
				'time' => $addon->getCreatedAt()->setTimezone(new DateTimeZone('UTC')),
				'author' => sprintf('noreply@componette.org (%s)', $addon->getAuthor()),
				'content' => $addon->getGithub() && $addon->getGithub()->getContentHtml(),
			];

			if ($addon->getGithub() && $addon->getGithub()->getDescription()) {
				$item->title = sprintf('%s - %s', $addon->getFullname(), $addon->getGithub()->getDescription());
			} else {
				$item->title = sprintf('%s', $addon->getFullname());
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
