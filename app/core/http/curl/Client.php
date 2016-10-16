<?php

namespace App\Core\Http\Curl;

final class Client
{

    /** @var array */
    private $options = [
        CURLOPT_USERAGENT => 'ComponetteClient-v1',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_RETURNTRANSFER => 1,
    ];

    /** @var array */
    private $headers = [
        'Content-type' => 'application/json',
        'Time-Zone' => 'Europe/Prague',
    ];

    /**
     * @param string $url
     * @param array $headers
     * @param array $opts
     * @return Response
     */
    public function makeRequest($url, array $headers = [], array $opts = [])
    {
        $ch = curl_init();
        $responseFactory = new ResponseFactory();

        // Set-up headers
        $_headers = array_merge($this->headers, $headers);
        array_walk($_headers, function (&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });
        $_headers = array_values($_headers);
        // Set-up cURL options
        $_opts = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $_headers,
            CURLOPT_HEADERFUNCTION => [$responseFactory, 'parseHeaders'],
        ];
        $_opts = $opts + $_opts + $this->options;
        curl_setopt_array($ch, $_opts);

        // Make request
        $result = curl_exec($ch);

        // Store information about request/response
        $responseFactory->setInfos(curl_getinfo($ch));

        // Close connection
        curl_close($ch);

        // Store response
        $responseFactory->setBody($result);

        return $responseFactory->create();
    }


}
