<?php

namespace App\Model\WebServices\Bower;

use App\Core\Http\Curl\Client;
use App\Core\Http\Curl\Response;
use App\Model\Exceptions\Runtime\WebServices\BowerException;

final class BowerClient
{

    const URL = 'https://bower.herokuapp.com';

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
        $url = self::URL . '/' . ltrim($uri, '/');
        $response = $this->curl->makeRequest($url, $headers, $opts);

        if ($response->getStatusCode() > 300) {
            throw new BowerException($response);
        }

        return $response;
    }

}
