<?php declare(strict_types = 1);

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

	public function __construct(Github $github)
	{
		$this->repo = new Url('https://github.com');
		$this->repo->appendPath($github->addon->author . '/' . $github->addon->name);
		$this->author = new Url('https://github.com');
		$this->author->appendPath($github->addon->author);
		$this->raw = new Url('https://raw.github.com');
		$this->raw->appendPath($github->addon->author . '/' . $github->addon->name);
	}

	public function getAuthorUrl(): Url
	{
		return $this->author;
	}

	public function getAuthorAvatarUrl(?int $size = null): string
	{
		if ($size) {
			return $this->author . '.png?size=' . intval($size);
		} else {
			return $this->author . '.png';
		}
	}

	public function getRepoUrl(): Url
	{
		return $this->repo;
	}

	public function getIssuesUrl(): string
	{
		return $this->repo . '/issues';
	}

	public function getPullRequestsUrl(): string
	{
		return $this->repo . '/pulls';
	}

	public function getCommitsUrl(): string
	{
		return $this->repo . '/commits/master';
	}

	public function getPulseUrl(): string
	{
		return $this->repo . '/pulse';
	}

	public function getStarsUrl(): string
	{
		return $this->repo . '/stargazers';
	}

	public function getNewReleaseUrl(): string
	{
		return $this->repo . '/releases/new';
	}

	public function getReleasesUrl(): string
	{
		return $this->repo . '/releases';
	}

	public function getReleaseUrl(string $tag): string
	{
		return $this->repo . '/releases/tag/' . $tag;
	}

	public function getWatchersUrl(): string
	{
		return $this->repo . '/watchers';
	}

	public function getForksUrl(): string
	{
		return $this->repo . '/network';
	}

	public function getBlobUrl(string $uri, string $branch = 'master'): string
	{
		return $this->repo . '/blob/' . $branch . '/' . $uri;
	}

	public function getFileUrl(?string $file, ?string $fragment = null): string
	{
		return $this->repo . $file . '/' . $fragment;
	}

	public function getRawUrl(string $file): string
	{
		return $this->raw . '/HEAD/' . $file;
	}

}
