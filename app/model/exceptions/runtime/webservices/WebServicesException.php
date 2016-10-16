<?php

namespace App\Model\Exceptions\Runtime\WebServices;

use App\Core\Http\Curl\Response;
use App\Model\Exceptions\RuntimeException;

abstract class WebServicesException extends RuntimeException
{

    /** @var Response */
    protected $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct();
        $this->response = $response;
    }

}
