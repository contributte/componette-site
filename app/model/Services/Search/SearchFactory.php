<?php

namespace App\Model\Services\Search;

use Nette\Http\Request;

final class SearchFactory
{

	/** @var Request */
	private $request;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @return Search
	 */
	public function create()
	{
		$search = new Search();

		// Pass parameters from request
		$search->by = $this->request->getQuery('search-by');
		$search->limit = $this->request->getQuery('search-limit');
		$search->q = $this->request->getQuery('q', NULL);

		return $search;
	}

}
