<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Tag;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tag')]
class Tag extends AbstractEntity
{

	#[ORM\Column(type: 'string', length: 100)]
	private string $name;

	#[ORM\Column(type: 'string', length: 20, nullable: true)]
	private ?string $color = null;

	#[ORM\Column(type: 'integer')]
	private int $priority = 0;

	#[ORM\Column(type: 'boolean')]
	private bool $highlighted = false;

	/** @var Collection<int, Addon> */
	#[ORM\ManyToMany(targetEntity: Addon::class, mappedBy: 'tags')]
	private Collection $addons;

	public function __construct(string $name)
	{
		$this->name = $name;
		$this->addons = new ArrayCollection();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getColor(): ?string
	{
		return $this->color;
	}

	public function setColor(?string $color): void
	{
		$this->color = $color;
	}

	public function getPriority(): int
	{
		return $this->priority;
	}

	public function setPriority(int $priority): void
	{
		$this->priority = $priority;
	}

	public function isHighlighted(): bool
	{
		return $this->highlighted;
	}

	public function setHighlighted(bool $highlighted): void
	{
		$this->highlighted = $highlighted;
	}

	/**
	 * @return Collection<int, Addon>
	 */
	public function getAddons(): Collection
	{
		return $this->addons;
	}

}
