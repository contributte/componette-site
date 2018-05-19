<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Nette\Utils\ArrayHash;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$composers}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 */
class GithubComposer extends AbstractEntity
{

	// Types
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Branches
	public const BRANCH_MASTER = 'master';

	/** @var ArrayHash|string[] */
	protected $json = [];

	/**
	 * VIRTUAL *****************************************************************
	 */

	protected function getterJson(): ArrayHash
	{
		return $this->json;
	}

	/**
	 * @return mixed
	 */
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
			if (func_num_args() > 1) return $default;
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
	protected function onLoad(array $data): void
	{
		parent::onLoad($data);

		if (isset($data['data'])) {
			$this->json = ArrayHash::from(json_decode($data['data']));
		}
	}

	protected function onBeforeInsert(): void
	{
		parent::onBeforeInsert();

		$json = $this->getRawProperty('json');
		if ($json) {
			$this->setRawValue('data', json_encode($json));
		}
	}

}
