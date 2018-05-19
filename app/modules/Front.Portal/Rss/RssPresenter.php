<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Rss;

use App\Modules\Front\Portal\Base\BasePresenter;
use App\Modules\Front\Portal\Rss\Controls\RssFeed\RssFeed;
use App\Modules\Front\Portal\Rss\Controls\RssFeed\RssFeedFactory;

final class RssPresenter extends BasePresenter
{

	/** @var RssFeedFactory @inject */
	public $rssFeedFactory;

	public function renderAuthor(string $author): void
	{
		$this->template->author = $author;
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
