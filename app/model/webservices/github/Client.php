<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\GithubException;

final class Client
{

    const URL = 'https://api.github.com';

    /** @var string */
    private $token;

    /**
     * @param string $token
     */
    function __construct($token = NULL)
    {
        $this->token = $token;
    }

    /**
     * @param string $uri
     * @return mixed (array|NULL)
     */
    public function makeRequest($uri)
    {
        $ch = curl_init();

        $headers = [
            'Content-type: application/json',
            'Time-Zone: Europe/Prague',
        ];

        if ($this->token) {
            $headers[] = 'Authorization: token ' . $this->token;
        }

        $url = self::URL . '/' . trim($uri, '/');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'ComponetteClient-v1',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ]);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] > 300) {
            throw new GithubException($uri, $headers, [], $info, $result);
        }

        if ($result) {
            return @json_decode($result, TRUE);
        } else {
            return NULL;
        }
    }

}