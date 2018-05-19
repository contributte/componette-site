<?php declare(strict_types = 1);

namespace App\Model\WebServices\Composer;

use App\Model\Exceptions\Runtime\WebServices\ComposerException;
use Contributte\Http\Curl\CurlClient;
use Contributte\Http\Curl\Response;

final class ComposerClient
{

	public const URL = 'https://packagist.org';

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

		if ($response->getStatusCode() > 300) {
			throw new ComposerException($response);
		}

		return $response;
	}

}
