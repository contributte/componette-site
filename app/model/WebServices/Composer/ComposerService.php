<?php

namespace App\Model\WebServices\Composer;

use App\Model\Exceptions\Runtime\WebServices\ComposerException;
use Contributte\Http\Curl\ExceptionResponse;
use Contributte\Http\Curl\Response;

final class ComposerService
{

	/** @var ComposerClient */
	private $client;

	/**
	 * @param ComposerClient $client
	 */
	public function __construct(ComposerClient $client)
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
	 * @param string $vendor
	 * @param string $repo
	 * @return Response
	 */
	public function repo($vendor, $repo)
	{
		return $this->call(sprintf('/packages/%s/%s.json', $vendor, $repo));
	}

	/**
	 * @param string $vendor
	 * @param string $repo
	 * @param string $version
	 * @return Response
	 */
	public function stats($vendor, $repo, $version = NULL)
	{
		if ($version) {
			return $this->call(sprintf('/packages/%s/%s/stats/%s.json', $vendor, $repo, $version));
		} else {
			return $this->call(sprintf('/packages/%s/%s/stats/all.json', $vendor, $repo));
		}
	}

}
