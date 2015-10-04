<?php

namespace App\Model\WebServices\Composer;

use App\Model\Exceptions\Runtime\ComposerException;

final class Client
{

    const URL = 'https://packagist.org';

    /**
     * @param string $uri
     * @return array
     */
    public function makeRequest($uri)
    {
        $ch = curl_init();

        $headers = [
            'Content-type: application/json',
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::URL . '/' . trim($uri, '/'),
            CURLOPT_USERAGENT => 'NettePackagesCentralRepositoryClient-v1',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => 1,
        ]);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] !== 200) {
            throw new ComposerException('Request failed');
        }

        return json_decode($result, TRUE);
    }

}