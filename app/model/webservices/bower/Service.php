<?php

namespace App\Model\WebServices\Bower;

use App\Model\Exceptions\Runtime\ComposerException;

final class Service
{

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
     * @return array|NULL
     */
    protected function call($uri)
    {
        try {
            return $this->client->makeRequest($uri);
        } catch (ComposerException $e) {
            return NULL;
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