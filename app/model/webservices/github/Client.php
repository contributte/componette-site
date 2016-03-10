<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\GithubException;

final class Client
{

    const VERSION = 'v3';
    const URL_API = 'https://api.github.com';
    const URL_AVATAR = 'https://avatars.githubusercontent.com';

    /** @var string */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token = NULL)
    {
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
     * @return array
     */
    public function makeRequest($url, array $headers = [], array $opts = [])
    {
        $ch = curl_init();

        $_headers = array_merge([
            'Content-type: application/json',
            'Time-Zone: Europe/Prague',
        ], $headers);

        if ($this->token) {
            $_headers[] = 'Authorization: token ' . $this->token;
        }

        $_opts = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'ComponetteClient-v1',
            CURLOPT_HTTPHEADER => $_headers,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ];
        curl_setopt_array($ch, $opts + $_opts);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] > 300) {
            throw new GithubException($url, $headers, [], $info, $result);
        }

        // Pure result
        if (strpos($info['content_type'], 'application/json') === FALSE) {
            return [$info, $result];
        }

        // Parse result from json
        if ($result) {
            return [$info, @json_decode($result, TRUE)];
        } else {
            return [$info, NULL];
        }
    }

}
