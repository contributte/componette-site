<?php

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Addon $addon                   {m:1 Addon::$composerStatistics}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property array $data
 * @property DateTime $createdAt
 * @property DateTime $publishedAt
 *
 * @property array $json                    {virtual}
 */
class ComposerStatistics extends AbstractEntity
{

	const TYPE_ALL = 'ALL';
	const TYPE_BRANCH = 'BRANCH';
	const TYPE_TAG = 'TAG';

	/** @var array */
	protected $json = [];

	/**
	 * @return array
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
			$this->json = json_decode($data['data']);
		}
	}

}
