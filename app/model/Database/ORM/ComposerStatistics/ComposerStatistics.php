<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\ArrayHash;

/**
 * @property int $id                        {primary}
 * @property Addon $addon                   {m:1 Addon::$composerStatistics}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 *
 * @property ArrayHash|string[]  $json      {virtual}
 */
class ComposerStatistics extends AbstractEntity
{

	// Types
	public const TYPE_ALL = 'ALL';
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Customs
	public const CUSTOM_ALL = 'ALL';

	/** @var ArrayHash|string[] */
	protected $json = [];

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @return ArrayHash|string[]
	 */
	protected function getterJson()
	{
		return $this->json;
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
			$this->json = ArrayHash::from(json_decode($data['data']));
		}
	}

	public function onBeforeInsert(): void
	{
		parent::onBeforeInsert();

		$json = $this->getRawProperty('json');
		if ($json) {
			$this->setRawValue('data', json_encode($json));
		}
	}

}
