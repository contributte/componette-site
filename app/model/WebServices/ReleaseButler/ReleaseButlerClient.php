<?php declare(strict_types = 1);

namespace App\Model\WebServices\ReleaseButler;

use App\Model\Exceptions\Runtime\WebServices\ReleaseButlerException;
use Contributte\Http\Curl\CurlClient;
use Contributte\Http\Curl\Response;

final class ReleaseButlerClient
{

	public const URL = 'https://releasebutler.now.sh';

	/** @var CurlClient */
	private $curl;

	public function __construct(CurlClient $curl)
	{
		$this->curl = $curl;
	}

	/**
	 * @param string[] $headers
	 * @param string[] $opts
	 */
	public function makeRequest(string $uri, array $headers = [], array $opts = []): Response
	{
		$uri = self::URL . '/' . ltrim($uri, '/');
		$response = $this->curl->makeRequest($uri, $headers, $opts);

		if ($response->getStatusCode() > 200) {
			throw new ReleaseButlerException($response);
		}

		return $response;
	}

}
