<?php declare(strict_types = 1);

namespace App\Modules\Front\Generator\Controls\Sitemap;

interface ISitemapFactory
{

	public function create(): Sitemap;

}
