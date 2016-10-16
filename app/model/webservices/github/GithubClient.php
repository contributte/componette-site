<?php

namespace App\Model\WebServices\Github;

use App\Core\Http\Curl\Client;
use App\Core\Http\Curl\Response;
use App\Model\Exceptions\Runtime\WebServices\GithubException;

final class GithubClient
{

    const VERSION = 'v3';
    const URL_API = 'https://api.github.com';
    const URL_AVATAR = 'https://avatars.githubusercontent.com';

    /** @var Client */
    private $curl;

    /** @var string */
    private $token;

    /**
     * @param Client $curl
     * @param $token
     */
    public function __construct(Client $curl, $token)
    {
        $this->curl = $curl;
        $this->token = $token;
    }

    /**
     * @param string $uri
     * @return string
     */
    public function getApiUrl($uri)
    {
        return self::URL_API . '/' . trim($uri, '/');
    }

    /**
     * @param string $username
     * @return string
     */
    public function getAvatarUrl($username)
    {
        return self::URL_AVATAR . '/' . trim($username, '/');
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $opts
     * @return Response
     */
    public function makeRequest($url, array $headers = [], array $opts = [])
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
