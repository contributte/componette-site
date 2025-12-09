<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\Tag\Tag;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddonRepository::class)]
#[ORM\Table(name: 'addon')]
class Addon extends AbstractEntity
{

	// Types
	public const TYPE_COMPOSER = 'COMPOSER';
	public const TYPE_BOWER = 'BOWER';
	public const TYPE_UNTYPE = 'UNTYPE';
	public const TYPE_UNKNOWN = 'UNKNOWN';

	// States
	public const STATE_ACTIVE = 'ACTIVE';
	public const STATE_ARCHIVED = 'ARCHIVED';
	public const STATE_QUEUED = 'QUEUED';

	// Github scheme
	public const GITHUB_REGEX = '^(?:(?:https?:\/\/)?(?:www\.)?github\.com\/)?([\w\d\-\.]+)\/([\w\d\-\.]+)/?$';

	#[ORM\Column(type: 'string', length: 20)]
	private string $type = self::TYPE_UNKNOWN;

	#[ORM\Column(type: 'string', length: 20)]
	private string $state = self::STATE_QUEUED;

	#[ORM\Column(type: 'string', length: 255)]
	private string $author;

	#[ORM\Column(type: 'string', length: 255)]
	private string $name;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $rating = null;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $createdAt;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $updatedAt = null;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $featuredAt = null;

	#[ORM\OneToOne(targetEntity: Github::class, mappedBy: 'addon', cascade: ['persist', 'remove'])]
	private ?Github $github = null;

	#[ORM\OneToOne(targetEntity: Composer::class, mappedBy: 'addon', cascade: ['persist', 'remove'])]
	private ?Composer $composer = null;

	/** @var Collection<int, ComposerStatistics> */
	#[ORM\OneToMany(targetEntity: ComposerStatistics::class, mappedBy: 'addon', cascade: ['persist', 'remove'])]
	#[ORM\OrderBy(['id' => 'DESC'])]
	private Collection $composerStatistics;

	/** @var Collection<int, Tag> */
	#[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'addons')]
	#[ORM\JoinTable(name: 'addon_tag')]
	private Collection $tags;

	public function __construct(string $author, string $name)
	{
		$this->author = $author;
		$this->name = $name;
		$this->createdAt = new DateTimeImmutable();
		$this->composerStatistics = new ArrayCollection();
		$this->tags = new ArrayCollection();
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function getState(): string
	{
		return $this->state;
	}

	public function setState(string $state): void
	{
		$this->state = $state;
	}

	public function getAuthor(): string
	{
		return $this->author;
	}

	public function setAuthor(string $author): void
	{
		$this->author = $author;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getRating(): ?int
	{
		return $this->rating;
	}

	public function setRating(?int $rating): void
	{
		$this->rating = $rating;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	public function getFeaturedAt(): ?DateTimeImmutable
	{
		return $this->featuredAt;
	}

	public function setFeaturedAt(?DateTimeImmutable $featuredAt): void
	{
		$this->featuredAt = $featuredAt;
	}

	public function getGithub(): ?Github
	{
		return $this->github;
	}

	public function setGithub(?Github $github): void
	{
		$this->github = $github;
	}

	public function getComposer(): ?Composer
	{
		return $this->composer;
	}

	public function setComposer(?Composer $composer): void
	{
		$this->composer = $composer;
	}

	/**
	 * @return Collection<int, ComposerStatistics>
	 */
	public function getComposerStatistics(): Collection
	{
		return $this->composerStatistics;
	}

	public function addComposerStatistics(ComposerStatistics $statistics): void
	{
		if (!$this->composerStatistics->contains($statistics)) {
			$this->composerStatistics->add($statistics);
			$statistics->setAddon($this);
		}
	}

	/**
	 * @return Collection<int, Tag>
	 */
	public function getTags(): Collection
	{
		return $this->tags;
	}

	public function addTag(Tag $tag): void
	{
		if (!$this->tags->contains($tag)) {
			$this->tags->add($tag);
		}
	}

	public function removeTag(Tag $tag): void
	{
		$this->tags->removeElement($tag);
	}

	// Virtual properties

	public function getFullname(): string
	{
		return $this->author . '/' . $this->name;
	}

	public function isComposer(): bool
	{
		return $this->type === self::TYPE_COMPOSER;
	}

	public function isBower(): bool
	{
		return $this->type === self::TYPE_BOWER;
	}

	public function getComposerLatestStatistics(): ?ComposerStatistics
	{
		$first = $this->composerStatistics->first();

		return $first !== false ? $first : null;
	}

}
