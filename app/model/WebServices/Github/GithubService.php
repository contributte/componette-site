<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\WebServices\GithubException;
use Contributte\Http\Curl\ExceptionResponse;
use Contributte\Http\Curl\Response;

final class GithubService
{

	// Mediatypes
	const MEDIATYPE_HTML = 'html';
	const MEDIATYPE_HTML_JSON = 'html+json';
	const MEDIATYPE_RAW = 'raw';

	/** @var GithubClient */
	private $client;

	/**
	 * @param GithubClient $client
	 */
	public function __construct(GithubClient $client)
	{
		$this->client = $client;
	}

	/**
	 * @param string $url
	 * @param array $headers
	 * @param array $opts
	 * @return Response
	 */
	protected function makeRequest($url, array $headers = [], array $opts = [])
	{
		try {
			return $this->client->makeRequest($url, $headers, $opts);
		} catch (GithubException $e) {
			return new ExceptionResponse($e);
		}
	}

	/**
	 * @param string $uri
	 * @param array $headers
	 * @param array $opts
	 * @return Response
	 */
	protected function call($uri, array $headers = [], array $opts = [])
	{
		return $this->makeRequest($this->client->getApiUrl($uri), $headers, $opts);
	}

	/**
	 * @param string $url
	 * @param array $headers
	 * @param array $opts
	 * @return Response[]
	 */
	protected function aggregate($url, array $headers = [], array $opts = [])
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
				if ($page['rel'] == 'next') {
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
	 * @param string $link
	 * @return array
	 */
	protected function parsePages($link)
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
	 * @param string $mediatype
	 * @return array
	 */
	protected function mediatype($mediatype)
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

	/**
	 * @param string $author
	 * @param string $repo
	 * @return Response
	 */
	public function repo($author, $repo)
	{
		return $this->call(sprintf('/repos/%s/%s', $author, $repo));
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @param string $mediatype
	 * @return Response
	 */
	public function readme($author, $repo, $mediatype = NULL)
	{
		$headers = $this->mediatype($mediatype);

		return $this->call(sprintf('/repos/%s/%s/readme', $author, $repo), $headers);
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @param string $path
	 * @param string $mediatype
	 * @return Response
	 */
	public function content($author, $repo, $path, $mediatype = NULL)
	{
		$headers = $this->mediatype($mediatype);

		return $this->call(sprintf('/repos/%s/%s/contents/%s', $author, $repo, $path), $headers);
	}

	/**
	 * @param string $filename
	 * @return Response
	 */
	public function download($filename)
	{
		return $this->makeRequest($this->client->getContentUrl($filename));
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @return Response
	 */
	public function composer($author, $repo)
	{
		return $this->download(sprintf('%s/%s/master/%s', $author, $repo, 'composer.json'));
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @return Response
	 */
	public function bower($author, $repo)
	{
		return $this->content($author, $repo, 'bower.json');
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @param int $page
	 * @return Response
	 */
	public function releases($author, $repo, $page = NULL)
	{
		if ($page) {
			return $this->call(sprintf('/repos/%s/%s/releases?page=%s', $author, $repo, $page));
		}

		return $this->call(sprintf('/repos/%s/%s/releases', $author, $repo));
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @param string $mediatype
	 * @return Response[]
	 */
	public function allReleases($author, $repo, $mediatype = NULL)
	{
		$headers = $this->mediatype($mediatype);

		return $this->aggregate(
			$this->client->getApiUrl(sprintf('/repos/%s/%s/releases', $author, $repo)),
			$headers
		);
	}

	/**
	 * @param string $author
	 * @param string $repo
	 * @return Response
	 */
	public function stargazers($author, $repo)
	{
		return $this->call(sprintf('/repos/%s/%s/stargazers', $author, $repo));
	}

	/**
	 * @param string $author
	 * @return Response
	 */
	public function user($author)
	{
		return $this->call(sprintf('/users/%s', $author));
	}

	/**
	 * @param string $author
	 * @param bool $content
	 * @return Response
	 */
	public function avatar($author, $content = TRUE)
	{
		$opts = [];

		if (!$content) {
			$opts[CURLOPT_FILETIME] = TRUE;
			$opts[CURLOPT_NOBODY] = TRUE;
		}

		return $this->makeRequest($this->client->getAvatarUrl($author), [], $opts);
	}

	/**
	 * @return Response
	 */
	public function limit()
	{
		return $this->call('/rate_limit');
	}

}
