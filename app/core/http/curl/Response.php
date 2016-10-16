<?php

namespace App\Core\Http\Curl;

class Response
{

    /** @var mixed */
    private $body;

    /** @var array */
    private $headers = [];

    /** @var array */
    private $infos = [];

    /**
     * @param mixed $body
     * @param array $headers
     * @param array $infos
     */
    public function __construct($body = NULL, array $headers = [], array $infos = [])
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->infos = $infos;
    }

    /**
     * GETTERS/SETTERS *********************************************************
     */

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasInfo($key)
    {
        return isset($this->infos[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getInfo($key)
    {
        if ($this->hasInfo($key)) {
            return $this->infos[$key];
        }

        return NULL;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasHeader($key)
    {
        return isset($this->headers[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getHeader($key)
    {
        if ($this->hasHeader($key)) {
            return $this->headers[$key];
        }

        return NULL;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return $this->body !== NULL;
    }

    /**
     * @return bool
     */
    public function isJson()
    {
        return $this->getInfo('content_type') == 'application/json';
    }

    /**
     * @return mixed
     */
    public function getJsonBody()
    {
        return @json_decode($this->getBody(), TRUE);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->getInfo('http_code') ?: 0;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->getStatusCode() === 200;
    }

}
