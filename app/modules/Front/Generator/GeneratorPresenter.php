<?php declare(strict_types = 1);

namespace App\Modules\Front\Generator;

use App\Modules\Front\Base\BasePresenter;
use App\Modules\Front\Generator\Controls\Sitemap\ISitemapFactory;
use App\Modules\Front\Generator\Controls\Sitemap\Sitemap;

final class GeneratorPresenter extends BasePresenter
{

	/** @var ISitemapFactory @inject */
	public $sitemapFactory;

	protected function createComponentSitemap(): Sitemap
	{
		return $this->sitemapFactory->create();
	}

}
