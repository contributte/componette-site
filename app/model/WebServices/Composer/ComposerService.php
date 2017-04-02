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
	 * @param string $owner
	 * @param string $repo
	 * @return Response
	 */
	public function repo($owner, $repo)
	{
		return $this->call("/packages/$owner/$repo.json");
	}

	/**
	 * @param string $owner
	 * @param string $repo
	 * @param string $version
	 * @return Response
	 */
	public function stats($owner, $repo, $version = NULL)
	{
		if ($version) {
			return $this->call("/packages/$owner/$repo/stats/$version.json");
		} else {
			return $this->call("/packages/$owner/$repo/stats/all.json");
		}
	}

}
