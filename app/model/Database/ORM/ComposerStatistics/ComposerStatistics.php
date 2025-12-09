<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\ArrayHash;
use Nette\Utils\Json;

#[ORM\Entity(repositoryClass: ComposerStatisticsRepository::class)]
#[ORM\Table(name: 'composer_statistics')]
class ComposerStatistics extends AbstractEntity
{

	// Types
	public const TYPE_ALL = 'ALL';
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Customs
	public const CUSTOM_ALL = 'ALL';

	#[ORM\ManyToOne(targetEntity: Addon::class, inversedBy: 'composerStatistics')]
	#[ORM\JoinColumn(name: 'addon_id', referencedColumnName: 'id', nullable: false)]
	private Addon $addon;

	#[ORM\Column(type: 'string', length: 20)]
	private string $type;

	#[ORM\Column(type: 'string', length: 100)]
	private string $custom;

	#[ORM\Column(type: 'text')]
	private string $data = '{}';

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $createdAt;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	private ?DateTimeImmutable $updatedAt = null;

	/** @var ArrayHash<string, mixed>|null */
	private ?ArrayHash $json = null;

	public function __construct(Addon $addon, string $type, string $custom)
	{
		$this->addon = $addon;
		$this->type = $type;
		$this->custom = $custom;
		$this->createdAt = new DateTimeImmutable();
	}

	public function getAddon(): Addon
	{
		return $this->addon;
	}

	public function setAddon(Addon $addon): void
	{
		$this->addon = $addon;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function getCustom(): string
	{
		return $this->custom;
	}

	public function setCustom(string $custom): void
	{
		$this->custom = $custom;
	}

	public function getData(): string
	{
		return $this->data;
	}

	public function setData(string $data): void
	{
		$this->data = $data;
		$this->json = null;
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

	/**
	 * @return ArrayHash<string, mixed>
	 */
	public function getJson(): ArrayHash
	{
		if ($this->json === null) {
			$this->json = ArrayHash::from((array) Json::decode($this->data));
		}

		return $this->json;
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public function setJson(array $data): void
	{
		$this->json = ArrayHash::from($data);
		$this->data = Json::encode($data);
	}

}
