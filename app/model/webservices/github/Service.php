<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\GithubException;

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
        } catch (GithubException $e) {
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

    /**
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function readme($owner, $repo)
    {
        return $this->call("/repos/$owner/$repo/readme");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param string $path
     * @return mixed
     */
    public function content($owner, $repo, $path)
    {
        return $this->call("/repos/$owner/$repo/contents/$path");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function composer($owner, $repo)
    {
        return $this->content($owner, $repo, 'composer.json');
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function bower($owner, $repo)
    {
        return $this->content($owner, $repo, 'bower.json');
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function releases($owner, $repo)
    {

        return $this->call("/repos/$owner/$repo/releases");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return mixed
     */
    public function stargazers($owner, $repo)
    {

        return $this->call("/repos/$owner/$repo/stargazers");
    }

    /**
     * @return mixed
     */
    public function limit()
    {

        return $this->call("/rate_limit");
    }

}
