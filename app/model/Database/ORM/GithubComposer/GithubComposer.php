<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use App\Model\Utils\Arrays;
use Nette\Utils\ArrayHash;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$composers}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 * @property DateTimeImmutable $createdAt   {default now}
 * @property DateTimeImmutable|NULL $updatedAt
 *
 * @property string $name                   {virtual}
 * @property ArrayHash $json                {virtual}
 */
class GithubComposer extends AbstractEntity
{

	// Types
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Branches
	public const BRANCH_MASTER = 'master';

	/**
	 * @var ArrayHash
	 * @phpstan-var ArrayHash<string, mixed>
	 */
	protected $json;

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @phpstan-return ArrayHash<string, mixed>
	 */
	protected function getterJson(): ArrayHash
	{
		return $this->json;
	}

	/**
	 * @param mixed[] $data
	 * @phpstan-param array<string, mixed> $data
	 */
	protected function setterJson(array $data): void
	{
		$this->json = ArrayHash::from($data);
	}

	protected function getterName(): ?string
	{
		return $this->json->name;
	}

	/**
	 * METHODS *****************************************************************
	 */

	/**
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get(string $key, $default = null)
	{
		if (!isset($this->json->{$key})) {
			if (func_num_args() > 1) {
				return $default;
			}
			throw new InvalidArgumentException(sprintf('Key "%s" not found in Composer\'s data', $key));
		}

		return $this->json->{$key};
	}

	/**
	 * EVENTS ******************************************************************
	 */

	/**
	 * @param string[] $data
	 */
	public function onLoad(array $data): void
	{
		parent::onLoad($data);

		if (isset($data['data'])) {
			$this->json = ArrayHash::from((array) json_decode($data['data']));
		} else {
			$this->json = new ArrayHash();
		}
	}

	public function onBeforeInsert(): void
	{
		parent::onBeforeInsert();
		$this->setRawValue('data', json_encode(Arrays::ensure($this->json)));
	}

}
