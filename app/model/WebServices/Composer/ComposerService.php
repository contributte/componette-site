<?php declare(strict_types = 1);

namespace App\Model\WebServices\Composer;

use App\Model\Exceptions\Runtime\WebServices\ComposerException;
use Contributte\Http\Curl\Response;

final class ComposerService
{

    /**
     * @var ComposerClient 
     */
    private $client;

    public function __construct(ComposerClient $client)
    {
        $this->client = $client;
    }

    protected function call(string $uri): Response
    {
        try {
            return $this->client->makeRequest($uri);
        } catch (ComposerException $e) {
            $response = new Response();
            $response->setError($e);

            return $response;
        }
    }

    public function repo(string $vendor, string $repo): Response
    {
        return $this->call(sprintf('/packages/%s/%s.json', $vendor, $repo));
    }

    public function stats(string $vendor, string $repo, ?string $version = null): Response
    {
        if ($version) {
            return $this->call(sprintf('/packages/%s/%s/stats/%s.json', $vendor, $repo, $version));
        } else {
            return $this->call(sprintf('/packages/%s/%s/stats/all.json', $vendor, $repo));
        }
    }

}
