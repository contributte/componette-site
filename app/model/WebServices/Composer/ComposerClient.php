<?php

namespace App\Model\WebServices\Composer;

use App\Model\Exceptions\Runtime\WebServices\ComposerException;
use Contributte\Http\Curl\CurlClient;
use Contributte\Http\Curl\Response;

final class ComposerClient
{

	const URL = 'https://packagist.org';

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
		$uri = self::URL . '/' . ltrim($uri, '/');
		$response = $this->curl->makeRequest($uri, $headers, $opts);

		if ($response->getStatusCode() > 300) {
			throw new ComposerException($response);
		}

		return $response;
	}

}
