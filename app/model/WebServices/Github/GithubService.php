<?php declare(strict_types = 1);

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\WebServices\GithubException;
use Contributte\Http\Curl\ExceptionResponse;
use Contributte\Http\Curl\Response;

class GithubService
{

	// Mediatypes
	public const MEDIATYPE_HTML = 'html';
	public const MEDIATYPE_HTML_JSON = 'html+json';
	public const MEDIATYPE_RAW = 'raw';

	/** @var GithubClient */
	private $client;

	public function __construct(GithubClient $client)
	{
		$this->client = $client;
	}

	/**
	 * @param string[] $headers
	 * @param string[] $opts
	 */
	protected function makeRequest(string $url, array $headers = [], array $opts = []): Response
	{
		try {
			return $this->client->makeRequest($url, $headers, $opts);
		} catch (GithubException $e) {
			return new ExceptionResponse($e);
		}
	}

	/**
	 * @param string[] $headers
	 * @param string[] $opts
	 */
	protected function call(string $uri, array $headers = [], array $opts = []): Response
	{
		return $this->makeRequest($this->client->getApiUrl($uri), $headers, $opts);
	}

	/**
	 * @param string[] $headers
	 * @param string[] $opts
	 * @return Response[]
	 */
	protected function aggregate(string $url, array $headers = [], array $opts = []): array
	{
		// Fire request
		$response = $this->makeRequest($url, $headers, $opts);

		// Empty response
		if (!$response) return [];

		// Create array of responses
		$responses = [$response];

		// Do we have any link in headers?
		$link = $response->getHeader('Link');
		if ($link) {
			// Parse Github style pages
			$pages = $this->parsePages($link);
			foreach ($pages as $page) {
				// Iterate over all pages and take only next pages
				if ($page['rel'] === 'next') {
					// Fetch next response
					$innerResponses = $this->aggregate($page['url'], $headers, $opts);
					// Append to current responses
					$responses = array_merge($responses, $innerResponses);
				}
			}
		}

		return $responses;
	}

	/**
	 * @return string[]
	 */
	protected function parsePages(string $link): array
	{
		preg_match_all('#<(.+\?page=(\d+))>;\srel=.((?:next|last|first)).#U', $link, $matches);
		if (!$matches) return [];

		$pages = [];
		foreach ($matches[1] as $n => $url) {
			$pages[] = [
				'url' => $url,
				'page' => $matches[2][$n],
				'rel' => $matches[3][$n],
			];
		}

		return $pages;
	}


	/**
	 * @return string[]
	 */
	protected function mediatype(?string $mediatype): array
	{
		switch ($mediatype) {
			case self::MEDIATYPE_HTML:
				return ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html'];
				break;

			case self::MEDIATYPE_HTML_JSON:
				return ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html+json'];
				break;

			case self::MEDIATYPE_RAW:
				return ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.raw'];
				break;

			default:
				return [];
		}
	}

	/**
	 * API *********************************************************************
	 */

	public function repo(string $author, string $repo): Response
	{
		return $this->call(sprintf('/repos/%s/%s', $author, $repo));
	}

	public function readme(string $author, string $repo, ?string $mediatype = null): Response
	{
		$headers = $this->mediatype($mediatype);

		return $this->call(sprintf('/repos/%s/%s/readme', $author, $repo), $headers);
	}

	public function content(string $author, string $repo, string $path, ?string $mediatype = null): Response
	{
		$headers = $this->mediatype($mediatype);

		return $this->call(sprintf('/repos/%s/%s/contents/%s', $author, $repo, $path), $headers);
	}

	public function download(string $filename): Response
	{
		return $this->makeRequest($this->client->getContentUrl($filename));
	}

	public function composer(string $author, string $repo): Response
	{
		return $this->download(sprintf('%s/%s/master/%s', $author, $repo, 'composer.json'));
	}

	public function bower(string $author, string $repo): Response
	{
		return $this->content($author, $repo, 'bower.json');
	}

	public function releases(string $author, string $repo, ?int $page = null): Response
	{
		if ($page) {
			return $this->call(sprintf('/repos/%s/%s/releases?page=%s', $author, $repo, $page));
		}

		return $this->call(sprintf('/repos/%s/%s/releases', $author, $repo));
	}

	/**
	 * @return Response[]
	 */
	public function allReleases(string $author, string $repo, ?string $mediatype = null): array
	{
		$headers = $this->mediatype($mediatype);

		return $this->aggregate(
			$this->client->getApiUrl(sprintf('/repos/%s/%s/releases', $author, $repo)),
			$headers
		);
	}

	public function stargazers(string $author, string $repo): Response
	{
		return $this->call(sprintf('/repos/%s/%s/stargazers', $author, $repo));
	}

	public function user(string $author): Response
	{
		return $this->call(sprintf('/users/%s', $author));
	}

	public function avatar(string $author, bool $content = true): Response
	{
		$opts = [];

		if (!$content) {
			$opts[CURLOPT_FILETIME] = true;
			$opts[CURLOPT_NOBODY] = true;
		}

		return $this->makeRequest($this->client->getAvatarUrl($author), [], $opts);
	}

	public function limit(): Response
	{
		return $this->call('/rate_limit');
	}

}
