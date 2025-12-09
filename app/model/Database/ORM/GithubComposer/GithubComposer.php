<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\ArrayHash;
use Nette\Utils\Json;

#[ORM\Entity(repositoryClass: GithubComposerRepository::class)]
#[ORM\Table(name: 'github_composer')]
class GithubComposer extends AbstractEntity
{

	// Types
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Branches
	public const BRANCH_MASTER = 'master';

	#[ORM\ManyToOne(targetEntity: Github::class, inversedBy: 'composers')]
	#[ORM\JoinColumn(name: 'github_id', referencedColumnName: 'id', nullable: false)]
	private Github $github;

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

	public function __construct(Github $github, string $type, string $custom)
	{
		$this->github = $github;
		$this->type = $type;
		$this->custom = $custom;
		$this->createdAt = new DateTimeImmutable();
	}

	public function getGithub(): Github
	{
		return $this->github;
	}

	public function setGithub(Github $github): void
	{
		$this->github = $github;
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

	public function getComposerName(): ?string
	{
		return $this->getJson()->name ?? null;
	}

	/**
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		$json = $this->getJson();
		if (!isset($json->{$key})) {
			if (func_num_args() > 1) {
				return $default;
			}

			throw new InvalidArgumentException(sprintf('Key "%s" not found in Composer\'s data', $key));
		}

		return $json->{$key};
	}

}
