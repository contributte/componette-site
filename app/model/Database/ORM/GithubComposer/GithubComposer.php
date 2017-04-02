<?php

namespace App\Model\Database\ORM\GithubComposer;

use App\Model\Database\ORM\AbstractEntity;
use App\Model\Database\ORM\Github\Github;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Github $github                 {m:1 Github::$composers}
 * @property string $type                   {enum self::TYPE*}
 * @property string $custom
 * @property array $data
 * @property DateTime $createdAt
 * @property DateTime $publishedAt
 */
class GithubComposer extends AbstractEntity
{

	const TYPE_ALL = 'ALL';
	const TYPE_BRANCH = 'BRANCH';
	const TYPE_TAG = 'TAG';

}
