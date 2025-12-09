<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Github;

use App\Model\Database\Helpers\GithubLinker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubComposer\GithubComposer;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GithubRepository::class)]
#[ORM\Table(name: 'github')]
class Github extends AbstractEntity
{

	#[ORM\OneToOne(targetEntity: Addon::class, inversedBy: 'github')]
	#[ORM\JoinColumn(name: 'addon_id', referencedColumnName: 'id', nullable: false)]
	private Addon $addon;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $description = null;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $contentRaw = null;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $contentHtml = null;

	#[ORM\Column(type: 'string', length: 255, nullable: true)]
	private ?string $homepage = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $stars = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $watchers = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $issues = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $forks = null;

	#[ORM\Column(type: 'boolean', nullable: true)]
	private ?bool $fork = null;

	#[ORM\Column(type: 'string', length: 50, nullable: true)]
	private ?string $language = null;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $createdAt = null;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $pushedAt = null;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $updatedAt = null;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $crawledAt;

	/** @var Collection<int, GithubRelease> */
	#[ORM\OneToMany(targetEntity: GithubRelease::class, mappedBy: 'github', cascade: ['persist', 'remove'])]
	#[ORM\OrderBy(['publishedAt' => 'DESC', 'tag' => 'DESC'])]
	private Collection $releases;

	/** @var Collection<int, GithubComposer> */
	#[ORM\OneToMany(targetEntity: GithubComposer::class, mappedBy: 'github', cascade: ['persist', 'remove'])]
	private Collection $composers;

	private ?GithubLinker $linker = null;

	public function __construct(Addon $addon)
	{
		$this->addon = $addon;
		$this->crawledAt = new DateTimeImmutable();
		$this->releases = new ArrayCollection();
		$this->composers = new ArrayCollection();
	}

	public function getAddon(): Addon
	{
		return $this->addon;
	}

	public function setAddon(Addon $addon): void
	{
		$this->addon = $addon;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getContentRaw(): ?string
	{
		return $this->contentRaw;
	}

	public function setContentRaw(?string $contentRaw): void
	{
		$this->contentRaw = $contentRaw;
	}

	public function getContentHtml(): ?string
	{
		return $this->contentHtml;
	}

	public function setContentHtml(?string $contentHtml): void
	{
		$this->contentHtml = $contentHtml;
	}

	public function getHomepage(): ?string
	{
		return $this->homepage;
	}

	public function setHomepage(?string $homepage): void
	{
		$this->homepage = $homepage;
	}

	public function getStars(): ?int
	{
		return $this->stars;
	}

	public function setStars(?int $stars): void
	{
		$this->stars = $stars;
	}

	public function getWatchers(): ?int
	{
		return $this->watchers;
	}

	public function setWatchers(?int $watchers): void
	{
		$this->watchers = $watchers;
	}

	public function getIssues(): ?int
	{
		return $this->issues;
	}

	public function setIssues(?int $issues): void
	{
		$this->issues = $issues;
	}

	public function getForks(): ?int
	{
		return $this->forks;
	}

	public function setForks(?int $forks): void
	{
		$this->forks = $forks;
	}

	public function isFork(): ?bool
	{
		return $this->fork;
	}

	public function setFork(?bool $fork): void
	{
		$this->fork = $fork;
	}

	public function getLanguage(): ?string
	{
		return $this->language;
	}

	public function setLanguage(?string $language): void
	{
		$this->language = $language;
	}

	public function getCreatedAt(): ?DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(?DateTimeImmutable $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getPushedAt(): ?DateTimeImmutable
	{
		return $this->pushedAt;
	}

	public function setPushedAt(?DateTimeImmutable $pushedAt): void
	{
		$this->pushedAt = $pushedAt;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	public function getCrawledAt(): DateTimeImmutable
	{
		return $this->crawledAt;
	}

	public function setCrawledAt(DateTimeImmutable $crawledAt): void
	{
		$this->crawledAt = $crawledAt;
	}

	/**
	 * @return Collection<int, GithubRelease>
	 */
	public function getReleases(): Collection
	{
		return $this->releases;
	}

	public function addRelease(GithubRelease $release): void
	{
		if (!$this->releases->contains($release)) {
			$this->releases->add($release);
			$release->setGithub($this);
		}
	}

	/**
	 * @return Collection<int, GithubComposer>
	 */
	public function getComposers(): Collection
	{
		return $this->composers;
	}

	public function addComposer(GithubComposer $composer): void
	{
		if (!$this->composers->contains($composer)) {
			$this->composers->add($composer);
			$composer->setGithub($this);
		}
	}

	// Virtual properties

	public function getLinker(): GithubLinker
	{
		if ($this->linker === null) {
			$this->linker = new GithubLinker($this);
		}

		return $this->linker;
	}

	public function getMasterComposer(): ?GithubComposer
	{
		foreach ($this->composers as $composer) {
			if ($composer->getType() === GithubComposer::TYPE_BRANCH && $composer->getCustom() === GithubComposer::BRANCH_MASTER) {
				return $composer;
			}
		}

		return null;
	}

}
