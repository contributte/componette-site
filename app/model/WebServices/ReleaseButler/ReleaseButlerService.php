<?php declare(strict_types = 1);

namespace App\Model\WebServices\ReleaseButler;

use App\Model\Exceptions\Runtime\WebServices\ReleaseButlerException;
use Contributte\Http\Curl\Response;

final class ReleaseButlerService
{

	/** @var ReleaseButlerClient */
	private $client;

	public function __construct(ReleaseButlerClient $client)
	{
		$this->client = $client;
	}

	protected function call(string $uri): Response
	{
		try {
			return $this->client->makeRequest($uri);
		} catch (ReleaseButlerException $e) {
			$response = new Response();
			$response->setError($e);

			return $response;
		}
	}

	public function changelog(string $repo, string $version): Response
	{
		return $this->call(sprintf('changelog?type=github&repo=%s&version=%s&theme=dracula', $repo, $version));
	}

}
