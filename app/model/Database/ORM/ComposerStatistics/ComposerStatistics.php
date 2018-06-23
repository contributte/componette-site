<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\ComposerStatistics;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nette\Utils\ArrayHash;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $id                        {primary}
 * @property Addon $addon                   {m:1 Addon::$composerStatistics}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property-read string $data
 * @property DateTimeImmutable $createdAt	{default now}
 * @property DateTimeImmutable|NULL $updatedAt
 *
 * @property ArrayHash $json               {virtual}
 */
class ComposerStatistics extends AbstractEntity
{

	// Types
	public const TYPE_ALL = 'ALL';
	public const TYPE_BRANCH = 'BRANCH';
	public const TYPE_TAG = 'TAG';

	// Customs
	public const CUSTOM_ALL = 'ALL';

	/** @var ArrayHash */
	protected $json;

	/**
	 * VIRTUAL *****************************************************************
	 */

	protected function getterJson(): ArrayHash
	{
		return $this->json;
	}

	/**
	 * @param mixed[] $data
	 */
	protected function setterJson(array $data): void
	{
		$this->json = ArrayHash::from($data);
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
		} else {
			$this->json = new ArrayHash();
		}
	}

	public function onBeforeInsert(): void
	{
		parent::onBeforeInsert();
		$this->setRawValue('data', json_encode((array) $this->json));
	}

}
