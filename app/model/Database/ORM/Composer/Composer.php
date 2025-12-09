<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Composer;

use App\Model\Database\Helpers\ComposerLinker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComposerRepository::class)]
#[ORM\Table(name: 'composer')]
class Composer extends AbstractEntity
{

	#[ORM\OneToOne(targetEntity: Addon::class, inversedBy: 'composer')]
	#[ORM\JoinColumn(name: 'addon_id', referencedColumnName: 'id', nullable: false)]
	private Addon $addon;

	#[ORM\Column(type: 'string', length: 255)]
	private string $name;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $description = null;

	#[ORM\Column(type: 'string', length: 50, nullable: true)]
	private ?string $type = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	private ?int $downloads = null;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $keywords = null;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $crawledAt;

	private ?ComposerLinker $linker = null;

	public function __construct(Addon $addon, string $name)
	{
		$this->addon = $addon;
		$this->name = $name;
		$this->crawledAt = new DateTimeImmutable();
	}

	public function getAddon(): Addon
	{
		return $this->addon;
	}

	public function setAddon(Addon $addon): void
	{
		$this->addon = $addon;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(?string $type): void
	{
		$this->type = $type;
	}

	public function getDownloads(): ?int
	{
		return $this->downloads;
	}

	public function setDownloads(?int $downloads): void
	{
		$this->downloads = $downloads;
	}

	public function getKeywords(): ?string
	{
		return $this->keywords;
	}

	public function setKeywords(?string $keywords): void
	{
		$this->keywords = $keywords;
	}

	public function getCrawledAt(): DateTimeImmutable
	{
		return $this->crawledAt;
	}

	public function setCrawledAt(DateTimeImmutable $crawledAt): void
	{
		$this->crawledAt = $crawledAt;
	}

	// Virtual properties

	public function getLinker(): ComposerLinker
	{
		if ($this->linker === null) {
			$this->linker = new ComposerLinker($this);
		}

		return $this->linker;
	}

}
