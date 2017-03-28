<?php

namespace App\Model\WebServices\Bower;

use App\Model\Exceptions\Runtime\WebServices\BowerException;
use Contributte\Http\Curl\CurlClient;
use Contributte\Http\Curl\Response;

final class BowerClient
{

	const URL = 'https://bower.herokuapp.com';

	/** @var CurlClient */
	private $curl;

	/**
	 * @param CurlClient $curl
	 */
	public function __construct(CurlClient $curl)
	{
		$this->curl = $curl;
	}

	/**
	 * @param string $uri
	 * @param array $headers
	 * @param array $opts
	 * @return Response
	 */
	public function makeRequest($uri, array $headers = [], array $opts = [])
	{
		$url = self::URL . '/' . ltrim($uri, '/');
		$response = $this->curl->makeRequest($url, $headers, $opts);

		if ($response->getStatusCode() > 300) {
			throw new BowerException($response);
		}

		return $response;
	}

}
