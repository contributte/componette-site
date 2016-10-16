<?php

namespace App\Model\WebServices\Github;

use App\Core\Http\Curl\ExceptionResponse;
use App\Core\Http\Curl\Response;
use App\Model\Exceptions\Runtime\WebServices\GithubException;

final class GithubService
{

    /** @var GithubClient */
    private $client;

    /**
     * @param GithubClient $client
     */
    public function __construct(GithubClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $opts
     * @return Response
     */
    protected function makeRequest($url, array $headers = [], array $opts = [])
    {
        try {
            return $this->client->makeRequest($url, $headers, $opts);
        } catch (GithubException $e) {
            return new ExceptionResponse($e);
        }
    }

    /**
     * @param string $uri
     * @param array $headers
     * @param array $opts
     * @return Response
     */
    protected function call($uri, array $headers = [], array $opts = [])
    {
        return $this->makeRequest($this->client->getApiUrl($uri), $headers, $opts);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $opts
     * @return Response[]
     */
    protected function aggregate($url, array $headers = [], array $opts = [])
    {
        // Fire request
        $response = $this->makeRequest($url, $headers, $opts);

        // Empty response
        if (!$response) return [];

        // Create array of responses
        $responses = [$response];

        // Do we have any link in headers?
        if (($link = $response->getHeader('Link'))) {

            // Parse Github style pages
            $pages = $this->parsePages($link);
            foreach ($pages as $page) {
                // Iterate over all pages and take only next pages
                if ($page['rel'] == 'next') {
                    // Fetch next response
                    $innerResponses = $this->aggregate($page['url'], $headers, $opts);
                    // Append to current responses
                    $responses = array_merge($responses, $innerResponses);
                }
            }
        }

        return $responses;
    }

    /**
     * @param string $link
     * @return array
     */
    protected function parsePages($link)
    {
        preg_match_all('#<(.+\?page=(\d+))>;\srel=.((?:next|last|first)).#U', $link, $matches);
        if (!$matches) return [];

        $pages = [];
        foreach ($matches[1] as $n => $url) {
            $pages[] = [
                'url' => $url,
                'page' => $matches[2][$n],
                'rel' => $matches[3][$n],
            ];
        }

        return $pages;
    }

    /**
     * API *********************************************************************
     */

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function repo($owner, $repo)
    {
        return $this->call("/repos/$owner/$repo");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function readme($owner, $repo, $type = NULL)
    {
        switch ($type) {
            case 'html':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html'];
                break;

            case 'html+json':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html+json'];
                break;

            case 'raw':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.raw'];
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
     * @return Response
     */
    public function content($owner, $repo, $path, $type = NULL)
    {
        switch ($type) {
            case 'html':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html'];
                break;

            case 'html+json':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.html+json'];
                break;

            case 'raw':
                $headers = ['Accept' => 'application/vnd.github.' . GithubClient::VERSION . '.raw'];
                break;

            default:
                $headers = [];
        }

        return $this->call("/repos/$owner/$repo/contents/$path", $headers);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function composer($owner, $repo)
    {
        return $this->content($owner, $repo, 'composer.json');
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function bower($owner, $repo)
    {
        return $this->content($owner, $repo, 'bower.json');
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function releases($owner, $repo, $page = NULL)
    {
        if ($page) {
            return $this->call("/repos/$owner/$repo/releases?page=$page");
        }

        return $this->call("/repos/$owner/$repo/releases");
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response[]
     */
    public function allReleases($owner, $repo)
    {
        return $this->aggregate($this->client->getApiUrl("/repos/$owner/$repo/releases"));
    }

    /**
     * @param string $owner
     * @param string $repo
     * @return Response
     */
    public function stargazers($owner, $repo)
    {
        return $this->call("/repos/$owner/$repo/stargazers");
    }

    /**
     * @param string $owner
     * @return Response
     */
    public function user($owner)
    {
        return $this->call("/users/$owner");
    }

    /**
     * @param string $owner
     * @return Response
     */
    public function avatar($owner)
    {
        return $this->makeRequest(
            $this->client->getAvatarUrl($owner),
            [],
            [CURLOPT_FILETIME => TRUE, CURLOPT_NOBODY => TRUE]
        );
    }

    /**
     * @return Response
     */
    public function limit()
    {
        return $this->call('/rate_limit');
    }

}
