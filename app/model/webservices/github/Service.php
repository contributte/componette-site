<?php

namespace App\Model\WebServices\Github;

use App\Model\Exceptions\Runtime\GithubException;

final class Service
{

    /** @var array */
    public $onException = [];

    /** @var Client */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $opts
     * @return array
     */
    protected function makeRequest($url, array $headers = [], array $opts = [])
    {
        try {
            return $this->client->makeRequest($url, $headers, $opts);
        } catch (GithubException $e) {
            // Trigger events
            foreach ($this->onException as $cb) {
                call_user_func($cb, $e);
            }

            // Return FALSE
            return FALSE;
        }
    }

    /**
     * @param string $uri
     * @param array $headers
     * @param array $opts
     * @return mixed
     */
    protected function call($uri, array $headers = [], array $opts = [])
    {
        list($info, $result) = $this->makeRequest($this->client->getApiUrl($uri), $headers, $opts);
        return $result;
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
    public function readme($owner, $repo, $type = NULL)
    {
        switch ($type) {
            case 'html':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.html'];
                break;

            case 'html+json':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.html+json'];
                break;

            case 'raw':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.raw'];
                break;

            default:
                $headers = [];
        }

        return $this->call("/repos/$owner/$repo/readme", $headers);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param string $path
     * @return mixed
     */
    public function content($owner, $repo, $path, $type = NULL)
    {
        switch ($type) {
            case 'html':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.html'];
                break;

            case 'html+json':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.html+json'];
                break;

            case 'raw':
                $headers = ['Accept: application/vnd.github.' . Client::VERSION . '.raw'];
                break;

            default:
                $headers = [];
        }

        return $this->call("/repos/$owner/$repo/contents/$path", $headers);
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
     * @param string $owner
     * @return mixed
     */
    public function user($owner)
    {
        return $this->call("/users/$owner");
    }

    /**
     * @param string $username
     * @return array
     */
    public function avatar($username)
    {
        return $this->makeRequest(
            $this->client->getAvatarUrl($username),
            [],
            [CURLOPT_FILETIME => TRUE, CURLOPT_NOBODY => TRUE]
        );
    }

    /**
     * @return mixed
     */
    public function limit()
    {
        return $this->call('/rate_limit');
    }

}
