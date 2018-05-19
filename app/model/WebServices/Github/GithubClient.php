<?php declare(strict_types = 1);

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\WebServices\GithubException;
use Contributte\Http\Curl\CurlClient;
use Contributte\Http\Curl\Response;

final class GithubClient
{

	public const VERSION = 'v3';
	public const URL_API = 'https://api.github.com';
	public const URL_AVATAR = 'https://avatars.githubusercontent.com';
	public const URL_CONTENT = 'https://raw.githubusercontent.com';

	/** @var CurlClient */
	private $curl;

	/** @var string */
	private $token;

	public function __construct(CurlClient $curl, string $token)
	{
		$this->curl = $curl;
		$this->token = $token;
	}

	public function getApiUrl(string $uri): string
	{
		return self::URL_API . '/' . trim($uri, '/');
	}

	public function getAvatarUrl(string $username): string
	{
		return self::URL_AVATAR . '/' . trim($username, '/');
	}

	public function getContentUrl(string $uri): string
	{
		return self::URL_CONTENT . '/' . trim($uri, '/');
	}

	/**
	 * @param string[] $headers
	 * @param string[] $opts
	 */
	public function makeRequest(string $url, array $headers = [], array $opts = []): Response
	{
		if ($this->token) {
			$headers['Authorization'] = 'token ' . $this->token;
		}

		$response = $this->curl->makeRequest($url, $headers, $opts);

		if ($response->getStatusCode() > 400) {
			throw new GithubException($response);
		}

		return $response;
	}

}
