<?php

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use App\Model\Exceptions\Logical\InvalidArgumentException;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$composers}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 * @property DateTime $createdAt            {default now}
 * @property DateTime|NULL $updatedAt
 *
 * @property ArrayHash|array $json          {virtual}
 * @property string $name                   {virtual}
 */
class GithubComposer extends AbstractEntity
{

	// Types
	const TYPE_BRANCH = 'BRANCH';
	const TYPE_TAG = 'TAG';

	// Branches
	const BRANCH_MASTER = 'master';

	/** @var array */
	protected $json = [];

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @return array
	 */
	protected function getterJson()
	{
		return $this->json;
	}

	/**
	 * @return array
	 */
	protected function getterName()
	{
		return $this->json->name;
	}

	/**
	 * METHODS *****************************************************************
	 */

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = NULL)
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
	 * @param array $data
	 * @return void
	 */
	protected function onLoad(array $data)
	{
		parent::onLoad($data);

		if (isset($data['data'])) {
			$this->json = ArrayHash::from(json_decode($data['data']));
		}
	}

	/**
	 * @return void
	 */
	protected function onBeforeInsert()
	{
		parent::onBeforeInsert();

		$json = $this->getRawProperty('json');
		if ($json) {
			$this->setRawValue('data', json_encode($json));
		}
	}

}
