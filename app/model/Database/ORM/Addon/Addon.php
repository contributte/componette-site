<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\Addon;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Composer\Composer;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatistics;
use App\Model\Database\ORM\Github\Github;
use App\Model\Database\ORM\Tag\Tag;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int $id                    {primary}
 * @property string $type               {enum self::TYPE_*} {default self::TYPE_UNKNOWN}
 * @property string $state              {enum self::STATE_*} {default self::STATE_QUEUED}
 * @property string $author
 * @property string $name
 * @property int|NULL $rating
 * @property DateTimeImmutable $createdAt        {default now}
 * @property DateTimeImmutable|NULL $updatedAt
 *
 * @property string $fullname                                     {virtual}
 * @property string $isComposer                                   {virtual}
 * @property string $isBower                                      {virtual}
 * @property ComposerStatistics|NULL $composerLatestStatistics    {virtual}
 *
 * @property Github|NULL $github                                    {1:1 Github::$addon}
 * @property Composer|NULL $composer                                {1:1 Composer::$addon}
 * @property ComposerStatistics[]|OneHasMany $composerStatistics    {1:m ComposerStatistics::$addon, orderBy=[id=DESC]}
 * @property ManyHasMany|Tag[] $tags                                {m:m Tag::$addons, isMain=true}
 */
class Addon extends AbstractEntity
{

	// Types
	public const TYPE_COMPOSER = 'COMPOSER';
	public const TYPE_BOWER = 'BOWER';
	public const TYPE_UNTYPE = 'UNTYPE';
	public const TYPE_UNKNOWN = 'UNKNOWN';

	// States
	public const STATE_ACTIVE = 'ACTIVE';
	public const STATE_ARCHIVED = 'ARCHIVED';
	public const STATE_QUEUED = 'QUEUED';

	// Github scheme
	public const GITHUB_REGEX = '^(?:(?:https?:\/\/)?(?:www\.)?github\.com\/)?([\w\d-\.]+)\/([\w\d-\.]+)$';

	protected function getterFullname(): string
	{
		return $this->author . '/' . $this->name;
	}

	protected function getterIsComposer(): bool
	{
		return $this->type === self::TYPE_COMPOSER;
	}

	protected function getterIsBower(): bool
	{
		return $this->type === self::TYPE_BOWER;
	}

	protected function getterComposerLatestStatistics(): ?ComposerStatistics
	{
		return $this->composerStatistics->get()->limitBy(1)->fetch();
	}

}
