<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Composer;

use App\Model\Database\Helpers\ComposerLinker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $id                            {primary}
 * @property Addon $addon                       {1:1 Addon::$composer, isMain=true}
 * @property string $name
 * @property string|NULL $description
 * @property string|NULL $type
 * @property int|NULL $downloads
 * @property string|NULL $keywords
 * @property DateTimeImmutable $crawledAt       {default now}
 *
 * @property ComposerLinker $linker             {virtual}
 */
class Composer extends AbstractEntity
{

	/** @var ComposerLinker|NULL */
	private $linker;

	/**
	 * VIRTUAL *****************************************************************
	 */

	protected function getterLinker(): ComposerLinker
	{
		if ($this->linker === null) {
			$this->linker = new ComposerLinker($this);
		}

		return $this->linker;
	}

}
