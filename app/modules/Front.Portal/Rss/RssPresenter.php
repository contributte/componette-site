<?php

namespace App\Modules\Front\Portal\Rss;

use App\Modules\Front\Portal\Base\BasePresenter;
use App\Modules\Front\Portal\Rss\Controls\RssFeed\RssFeed;
use App\Modules\Front\Portal\Rss\Controls\RssFeed\RssFeedFactory;

final class RssPresenter extends BasePresenter
{

	/** @var RssFeedFactory @inject */
	public $rssFeedFactory;

	/**
	 * @param string $author
	 * @return void
	 */
	public function renderAuthor($author)
	{
		$this->template->author = $author;
	}

	/**
	 * @return RssFeed
	 */
	protected function createComponentAuthor()
	{
		$control = $this->rssFeedFactory->createByAuthor($this->getParameter('author'));
		$control->setLink($this->link('//:Front:Home:default'));

		return $control;
	}

	/**
	 * @return RssFeed
	 */
	protected function createComponentNewest()
	{
		$control = $this->rssFeedFactory->createNewest();
		$control->setLink($this->link('//:Front:Home:default'));

		return $control;
	}

}
