<?php declare(strict_types = 1);

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$releases}
 * @property int $gid
 * @property string $name
 * @property string $tag
 * @property bool $draft
 * @property bool $prerelease
 * @property string $body
 * @property DateTimeImmutable $createdAt
 * @property DateTimeImmutable $publishedAt
 * @property DateTimeImmutable $crawledAt            {default now}
 */
class GithubRelease extends AbstractEntity
{

}
