<?php

namespace App\Model\WebServices\Bower;

use App\Core\Http\Curl\ExceptionResponse;
use App\Core\Http\Curl\Response;
use App\Model\Exceptions\Runtime\WebServices\ComposerException;

final class BowerService
{

	/** @var BowerClient */
	private $client;

	/**
	 * @param BowerClient $client
	 */
	public function __construct(BowerClient $client)
	{
		$this->client = $client;
	}

	/**
	 * @param string $uri
	 * @return Response
	 */
	protected function call($uri)
	{
		try {
			return $this->client->makeRequest($uri);
		} catch (ComposerException $e) {
			return new ExceptionResponse($e);
		}
	}

	/**
	 * @param string $name
	 * @return Response
	 */
	public function repo($name)
	{
		return $this->call("/packages/$name");
	}

	/**
	 * @param string $name
	 * @return Response
	 */
	public function search($name)
	{
		return $this->call("/packages/search/$name");
	}

}
