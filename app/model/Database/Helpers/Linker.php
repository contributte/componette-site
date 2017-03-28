<?php

namespace App\Model\Addons;

use Contributte\Http\Url;
use App\Model\Database\ORM\Github\Github;

final class Linker
{

	/** @var Github */
	private $github;

	/** @var Url */
	private $repo;

	/** @var Url */
	private $owner;

	/** @var Url */
	private $raw;

	/**
	 * @param Github $github
	 */
	public function __construct(Github $github)
	{
		$this->github = $github;
		$this->repo = new Url('https://github.com');
		$this->repo->appendPath($github->addon->owner . '/' . $github->addon->name);
		$this->owner = new Url('https://github.com');
		$this->owner->appendPath($github->addon->owner);
		$this->raw = new Url('https://raw.github.com');
		$this->raw->appendPath($github->addon->owner . '/' . $github->addon->name);
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
	public function getRepoUrl()
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
		return $this->repo . "/blob/$branch/$uri";
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
