<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\GithubException;

final class Client
{

    const VERSION = 'v3';
    const URL = 'https://api.github.com';

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
     * @param array $headers
     * @return mixed (array|NULL)
     */
    public function makeRequest($uri, array $headers = [])
    {
        $ch = curl_init();

        $_headers = array_merge([
            'Content-type: application/json',
            'Time-Zone: Europe/Prague',
        ], $headers);

        if ($this->token) {
            $_headers[] = 'Authorization: token ' . $this->token;
        }

        $url = self::URL . '/' . trim($uri, '/');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'ComponetteClient-v1',
            CURLOPT_HTTPHEADER => $_headers,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ]);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] > 300) {
            throw new GithubException($uri, $headers, [], $info, $result);
        }

        // Pure result
        if (strpos($info['content_type'], 'application/json') === FALSE) {
            return $result;
        }

        // Parse result from json
        if ($result) {
            return @json_decode($result, TRUE);
        } else {
            return NULL;
        }
    }

}
