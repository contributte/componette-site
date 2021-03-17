<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\News;

use Nette\SmartObject;
use Nette\Utils\DateTime;
use SimpleXMLElement;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateInject=true)
 */
class LoadFromRss
{

	use SmartObject;

	private const FEED = 'https://blog.nette.org/cs/feed/rss';

	public function process(): ?Article
	{
		$rss = $this->load();
		if ($rss) {
			$items = $rss->xpath('channel/item');
			$latest = $items && isset($items[0]) ? $items[0] : null;
			if ($latest) {
				$article = (array)$latest->children();
				return new Article($article['title'], $article['description'], $article['link'], $this->date($article));
			}
		}

		return null;
	}

	/**
	 * @param array<string> $article
	 */
	private function date(array $article): ?DateTime
	{
		$date = DateTime::createFromFormat(DateTime::RSS, $article['pubDate']);
		if ($date) {
			return $date;
		}

		return null;
	}

	private function load(): ?SimpleXMLElement
	{
		$context = stream_context_create(['http' => ['timeout' => 5]]);
		$data = file_get_contents(self::FEED, false, $context);
		if ($data) {
			$xml = simplexml_load_string($data);
			if ($xml) {
				return $xml;
			}
		}

		return null;
	}

}
