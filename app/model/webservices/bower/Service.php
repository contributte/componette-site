<?php

namespace App\Model\WebServices\Bower;

use App\Model\Exceptions\Runtime\ComposerException;

final class Service
{

    /** @var array */
    public $onException = [];

    /** @var Client */
    private $client;

    /**
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @return mixed
     */
    protected function call($uri)
    {
        try {
            return $this->client->makeRequest($uri);
        } catch (ComposerException $e) {
            // Trigger events
            foreach ($this->onException as $cb) {
                call_user_func($cb, $e);
            }

            // Return FALSE
            return FALSE;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function repo($name)
    {
        return $this->call("/packages/$name");
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function search($name)
    {
        return $this->call("/packages/search/$name");
    }

}