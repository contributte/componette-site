<?php

namespace App\Modules\Front\Generator;

use App\Modules\Front\Base\BasePresenter;
use App\Modules\Front\Portal\Controls\Sitemap\ISitemapFactory;
use App\Modules\Front\Portal\Controls\Sitemap\Sitemap;

final class GeneratorPresenter extends BasePresenter
{

	/** @var ISitemapFactory @inject */
	public $sitemapFactory;

	/**
	 * @return Sitemap
	 */
	protected function createComponentSitemap()
	{
		return $this->sitemapFactory->create();
	}

}
