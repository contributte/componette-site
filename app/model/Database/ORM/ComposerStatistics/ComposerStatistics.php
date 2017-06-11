<?php

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Addon $addon                   {m:1 Addon::$composerStatistics}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 * @property DateTime $createdAt            {default now}
 * @property DateTime|NULL $updatedAt
 *
 * @property ArrayHash|array $json          {virtual}
 */
class ComposerStatistics extends AbstractEntity
{

	// Types
	const TYPE_ALL = 'ALL';
	const TYPE_BRANCH = 'BRANCH';
	const TYPE_TAG = 'TAG';

	// Customs
	const CUSTOM_ALL = 'ALL';

	/** @var ArrayHash|array */
	protected $json = [];

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @return ArrayHash|array
	 */
	protected function getterJson()
	{
		return $this->json;
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
