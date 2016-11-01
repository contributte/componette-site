<?php

namespace App\Model\WebServices\Composer;

use App\Core\Http\Curl\Client;
use App\Core\Http\Curl\Response;
use App\Model\Exceptions\Runtime\WebServices\ComposerException;

final class ComposerClient
{

    const URL = 'https://packagist.org';

    /** @var Client */
    private $curl;

    /**
     * @param Client $curl
     */
    public function __construct(Client $curl)
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
