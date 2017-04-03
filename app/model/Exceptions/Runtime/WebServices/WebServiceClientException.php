<?php

namespace App\Model\Exceptions\Runtime\WebServices;

use App\Model\Exceptions\RuntimeException;
use Contributte\Http\Curl\Response;

abstract class WebServiceClientException extends RuntimeException
{

	/** @var Response */
	protected $response;

	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		parent::__construct();
		$this->response = $response;
	}

}
