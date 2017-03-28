<?php

namespace App\Model\Database\ORM\GithubRelease;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$releases}
 * @property int $gid
 * @property string $name
 * @property string $tag
 * @property bool $draft
 * @property bool $prerelease
 * @property string $body
 * @property DateTime $createdAt
 * @property DateTime $publishedAt
 * @property DateTime $crawledAt            {default now}
 */
class GithubRelease extends AbstractEntity
{

}
