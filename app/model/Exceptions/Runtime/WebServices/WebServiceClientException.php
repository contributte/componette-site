<?php declare(strict_types = 1);

namespace App\Model\Exceptions\Runtime\WebServices;

use App\Model\Exceptions\RuntimeException;
use Contributte\Http\Curl\Response;

abstract class WebServiceClientException extends RuntimeException
{

    /**
     * @var Response 
     */
    protected $response;

    public function __construct(Response $response)
    {
        parent::__construct();
        $this->response = $response;
    }

}
