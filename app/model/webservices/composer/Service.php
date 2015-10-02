<?php

namespace App\Model\WebServices\Composer;

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
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function repo($owner, $repo)
    {
        return $this->call("/repos/$owner/$repo");
    }

}