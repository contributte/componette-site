<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Search;

interface ISearchFactory
{

	public function create(): Search;

}
