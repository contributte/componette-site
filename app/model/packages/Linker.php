<?php

namespace App\Model\Packages;

use App\Core\Http\Url;

final class Linker
{

    /** @var Url */
    private $repo;

    /** @var Url */
    private $owner;

    /**
     * @param string $repository
     * @param string $owner
     */
    function __construct($repository, $owner)
    {
        $this->repo = new Url('https://github.com');
        $this->repo->appendPath($repository);
        $this->owner = new Url('https://github.com');
        $this->owner->appendPath($owner);
    }

    /**
     * @return string
     */
    public function getOwnerUrl()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getOwnerAvatarUrl($size = NULL)
    {
        if ($size) {
            return $this->owner . '.png?size=' . intval($size);
        } else {
            return $this->owner . '.png';
        }
    }

    /**
     * @return string
     */
    public function getIssuesUrl()
    {
        return $this->repo . '/issues';
    }

    /**
     * @return string
     */
    public function getPullRequestsUrl()
    {
        return $this->repo . '/pulls';
    }

    /**
     * @return string
     */
    public function getCommitsUrl()
    {
        return $this->repo . '/commits/master';
    }

    /**
     * @return string
     */
    public function getPulseUrl()
    {
        return $this->repo . '/pulse';
    }

    /**
     * @return string
     */
    public function getStarsUrl()
    {
        return $this->repo . '/stargazers';
    }

    /**
     * @return string
     */
    public function getReleasesUrl()
    {
        return $this->repo . '/releases';
    }

    /**
     * @return string
     */
    public function getWatchersUrl()
    {
        return $this->repo . '/watchers';
    }

    /**
     * @return string
     */
    public function getForksUrl()
    {
        return $this->repo . '/network';
    }

}