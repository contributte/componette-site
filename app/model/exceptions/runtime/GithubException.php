<?php

namespace App\Model\Exceptions\Runtime;

use App\Model\Exceptions\RuntimeException;

class GithubException extends RuntimeException
{

    /** @var string */
    protected $url;

    /** @var array */
    protected $headers;

    /** @var array */
    protected $data;

    /** @var array */
    protected $info;

    /** @var mixed */
    protected $result;

    /**
     * @param string $url
     * @param array $headers
     * @param array $data
     * @param array $info
     * @param mixed $result
     */
    public function __construct($url, array $headers, array $data, array $info, $result = NULL)
    {
        parent::__construct();
        $this->url = $url;
        $this->headers = $headers;
        $this->data = $data;
        $this->info = $info;
        $this->result = $result;
    }

}
