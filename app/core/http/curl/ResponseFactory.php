<?php

namespace App\Core\Http\Curl;

final class ResponseFactory
{

    /** @var mixed */
    private $body;

    /** @var array */
    private $headers = [];

    /** @var array */
    private $infos = [];

    /**
     * @param array $infos
     */
    public function setInfos(array $infos)
    {
        $this->infos = $infos;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $handle
     * @param string $header
     * @return int
     */
    public function parseHeaders($handle, $header)
    {
        preg_match('#^(.+):(.+)$#U', $header, $matches);
        if ($matches) {
            list($whole, $key, $value) = $matches;
            $this->headers[trim($key)] = trim($value);
        }

        return strlen($header);
    }

    /**
     * @return Response
     */
    public function create()
    {
        return new Response($this->body, $this->headers, $this->infos);
    }
}
