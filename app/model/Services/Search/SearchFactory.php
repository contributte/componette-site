<?php declare(strict_types = 1);

namespace App\Model\Services\Search;

use Nette\Http\Request;

final class SearchFactory
{

	/** @var Request */
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function create(): Search
	{
		$search = new Search();

		// Pass parameters from request
		$search->by = $this->request->getQuery('search-by');
		$search->limit = $this->request->getQuery('search-limit');
		$search->q = $this->request->getQuery('q') ?? null;

		return $search;
	}

}
