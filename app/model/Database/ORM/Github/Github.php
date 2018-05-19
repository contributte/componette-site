<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Github;

use App\Model\Database\Helpers\GithubLinker;
use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubComposer\GithubComposer;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use Nextras\Dbal\Utils\DateTimeImmutable;
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
 * @property DateTimeImmutable|NULL $createdAt
 * @property DateTimeImmutable|NULL $pushedAt
 * @property DateTimeImmutable|NULL $updatedAt
 * @property DateTimeImmutable $crawledAt               {default now}
 *
 * @property GithubRelease[]|OneHasMany $releases       {1:m GithubRelease::$github, orderBy=[publishedAt=DESC, tag=DESC]}
 * @property GithubComposer[]|OneHasMany $composers     {1:m GithubComposer::$github}
 *
 * @property GithubLinker $linker                       {virtual}
 * @property GithubComposer|NULL $masterComposer        {virtual}
 */
class Github extends AbstractEntity
{

	/** @var GithubLinker */
	private $linker;

	/**
	 * VIRTUAL *****************************************************************
	 */

	protected function getterLinker(): GithubLinker
	{
		if (!$this->linker) {
			$this->linker = new GithubLinker($this);
		}

		return $this->linker;
	}

	protected function getterMasterComposer(): ?GithubComposer
	{
		return $this->composers->get()->getBy([
			'type' => GithubComposer::TYPE_BRANCH,
			'custom' => GithubComposer::BRANCH_MASTER,
		]);
	}

}
