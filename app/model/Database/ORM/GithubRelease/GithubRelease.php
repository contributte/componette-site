<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GithubReleaseRepository::class)]
#[ORM\Table(name: 'github_release')]
class GithubRelease extends AbstractEntity
{

	#[ORM\ManyToOne(targetEntity: Github::class, inversedBy: 'releases')]
	#[ORM\JoinColumn(name: 'github_id', referencedColumnName: 'id', nullable: false)]
	private Github $github;

	#[ORM\Column(type: 'integer')]
	private int $gid;

	#[ORM\Column(type: 'string', length: 255)]
	private string $name;

	#[ORM\Column(type: 'string', length: 100)]
	private string $tag;

	#[ORM\Column(type: 'boolean')]
	private bool $draft = false;

	#[ORM\Column(type: 'boolean')]
	private bool $prerelease = false;

	#[ORM\Column(type: 'text')]
	private string $body = '';

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $createdAt;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $publishedAt;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $crawledAt;

	public function __construct(Github $github, int $gid, string $name, string $tag)
	{
		$this->github = $github;
		$this->gid = $gid;
		$this->name = $name;
		$this->tag = $tag;
		$this->createdAt = new DateTimeImmutable();
		$this->publishedAt = new DateTimeImmutable();
		$this->crawledAt = new DateTimeImmutable();
	}

	public function getGithub(): Github
	{
		return $this->github;
	}

	public function setGithub(Github $github): void
	{
		$this->github = $github;
	}

	public function getGid(): int
	{
		return $this->gid;
	}

	public function setGid(int $gid): void
	{
		$this->gid = $gid;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getTag(): string
	{
		return $this->tag;
	}

	public function setTag(string $tag): void
	{
		$this->tag = $tag;
	}

	public function isDraft(): bool
	{
		return $this->draft;
	}

	public function setDraft(bool $draft): void
	{
		$this->draft = $draft;
	}

	public function isPrerelease(): bool
	{
		return $this->prerelease;
	}

	public function setPrerelease(bool $prerelease): void
	{
		$this->prerelease = $prerelease;
	}

	public function getBody(): string
	{
		return $this->body;
	}

	public function setBody(string $body): void
	{
		$this->body = $body;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getPublishedAt(): DateTimeImmutable
	{
		return $this->publishedAt;
	}

	public function setPublishedAt(DateTimeImmutable $publishedAt): void
	{
		$this->publishedAt = $publishedAt;
	}

	public function getCrawledAt(): DateTimeImmutable
	{
		return $this->crawledAt;
	}

	public function setCrawledAt(DateTimeImmutable $crawledAt): void
	{
		$this->crawledAt = $crawledAt;
	}

}
