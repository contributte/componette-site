<?php

namespace App\Model\WebServices\Composer;

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
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function repo($owner, $repo)
    {
        return $this->call("/packages/$owner/$repo.json");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param string $version
     * @return mixed
     */
    public function stats($owner, $repo, $version = NULL)
    {
        if ($version) {
            return $this->call("/packages/$owner/$repo/stats/$version.json");
        } else {
            return $this->call("/packages/$owner/$repo/stats/all.json");
        }
    }

}
