<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss;

use App\Modules\Front\Base\BasePresenter;
use App\Modules\Front\Rss\Controls\RssFeed\RssFeed;
use App\Modules\Front\Rss\Controls\RssFeed\RssFeedFactory;

final class RssPresenter extends BasePresenter
{

	/** @var RssFeedFactory @inject */
	public $rssFeedFactory;

	public function renderAuthor(string $author): void
	{
		$this->getHttpResponse()->setContentType('application/rss+xml', 'utf-8');

		$this->template->author = $author;
	}

	public function renderNewest(): void
	{
		$this->getHttpResponse()->setContentType('application/rss+xml', 'utf-8');
	}

	protected function createComponentAuthor(): RssFeed
	{
		$control = $this->rssFeedFactory->createByAuthor($this->getParameter('author'));
		$control->setLink($this->link('//:Front:Home:default'));

		return $control;
	}

	protected function createComponentNewest(): RssFeed
	{
		$control = $this->rssFeedFactory->createNewest();
		$control->setLink($this->link('//:Front:Home:default'));

		return $control;
	}

}
