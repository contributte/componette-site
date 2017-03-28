<?php

namespace App\Model\Database\ORM\Github;

use App\Model\Addons\Extra;
use App\Model\Addons\ExtraComposer;
use App\Model\Addons\Linker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use Nette\Utils\DateTime;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int $id                                    {primary}
 * @property Addon $addon                               {1:1 Addon::$github, isMain=true}
 * @property string|NULL $description
 * @property string|NULL $contentRaw
 * @property string|NULL $contentHtml
 * @property string|NULL $homepage
 * @property int|NULL $stars
 * @property int|NULL $watchers
 * @property int|NULL $issues
 * @property int|NULL $forks
 * @property bool|NULL $fork
 * @property string|NULL $language
 * @property Extra|NULL $extra
 * @property DateTime|NULL $createdAt
 * @property DateTime|NULL $pushedAt
 * @property DateTime|NULL $updatedAt
 * @property DateTime $crawledAt                        {default now}
 *
 * @property GithubRelease[]|OneHasMany $releases       {1:m GithubRelease::$github, orderBy=[tag, DESC]}
 *
 * @property Linker $linker                             {virtual}
 * @property ExtraComposer $composer                    {virtual}
 */
class Github extends AbstractEntity
{

	/** @var Linker */
	private $linker;

	/** @var ExtraComposer */
	private $composer;

	/**
	 * VIRTUAL *****************************************************************
	 */

	/**
	 * @return Linker
	 */
	protected function getterLinker()
	{
		if (!$this->linker) {
			$this->linker = new Linker($this);
		}

		return $this->linker;
	}

	/**
	 * @return ExtraComposer
	 */
	protected function getterComposer()
	{
		if (!$this->composer) {
			$this->composer = new ExtraComposer($this->extra->get('composer', []));
		}

		return $this->composer;
	}

	/**
	 * EVENTS ******************************************************************
	 */

	/**
	 * Called on load entity from storage
	 *
	 * @param array $data
	 * @return void
	 */
	protected function onLoad(array $data)
	{
		$data['extra'] = new Extra($data['extra'] ? json_decode($data['extra'], TRUE) : []);

		parent::onLoad($data);
	}

	/**
	 * Called before persist to storage
	 *
	 * @return void
	 */
	protected function onBeforePersist()
	{
		parent::onBeforePersist();

		if (($extra = $this->getRawProperty('extra'))) {
			$this->setRawValue('extra', $extra->export());
		} else {
			$this->setRawValue('extra', NULL);
		}

		$this->crawledAt = new DateTime();
	}

}
