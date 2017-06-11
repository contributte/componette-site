<?php

namespace App\Model\Database\Helpers;

use App\Model\Database\ORM\Github\Github;
use Contributte\Http\Url;

final class GithubLinker
{

	/** @var Url */
	private $repo;

	/** @var Url */
	private $author;

	/** @var Url */
	private $raw;

	/**
	 * @param Github $github
	 */
	public function __construct(Github $github)
	{
		$this->repo = new Url('https://github.com');
		$this->repo->appendPath($github->addon->author . '/' . $github->addon->name);
		$this->author = new Url('https://github.com');
		$this->author->appendPath($github->addon->author);
		$this->raw = new Url('https://raw.github.com');
		$this->raw->appendPath($github->addon->author . '/' . $github->addon->name);
	}

	/**
	 * @return Url
	 */
	public function getAuthorUrl(): Url
	{
		return $this->author;
	}

	/**
	 * @param int $size
	 * @return string
	 */
	public function getAuthorAvatarUrl(int $size = NULL)
	{
		if ($size) {
			return $this->author . '.png?size=' . intval($size);
		} else {
			return $this->author . '.png';
		}
	}

	/**
	 * @return Url
	 */
	public function getRepoUrl(): Url
	{
		return $this->repo;
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
	public function getNewReleaseUrl()
	{
		return $this->repo . '/releases/new';
	}

	/**
	 * @return string
	 */
	public function getReleasesUrl()
	{
		return $this->repo . '/releases';
	}

	/**
	 * @param string $tag
	 * @return string
	 */
	public function getReleaseUrl($tag)
	{
		return $this->repo . '/releases/tag/' . $tag;
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

	/**
	 * @param string $uri
	 * @param string $branch
	 * @return string
	 */
	public function getBlobUrl($uri, $branch = 'master')
	{
		return $this->repo . '/blob/' . $branch . ' /' . $uri;
	}

	/**
	 * @param string $file
	 * @param string $fragment
	 * @return string
	 */
	public function getFileUrl($file, $fragment = NULL)
	{
		return $this->repo . $file . '/' . $fragment;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	public function getRawUrl($file)
	{
		return $this->raw . '/HEAD/' . $file;
	}

}
