<?php declare(strict_types = 1);

namespace App\Model\Database\ORM;

use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Composer\ComposerRepository;
use App\Model\Database\ORM\ComposerStatistics\ComposerStatisticsRepository;
use App\Model\Database\ORM\Github\GithubRepository;
use App\Model\Database\ORM\GithubComposer\GithubComposerRepository;
use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\Database\ORM\Tag\TagRepository;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read AddonRepository $addon
 * @property-read ComposerRepository $composer
 * @property-read ComposerStatisticsRepository $composerStatistics
 * @property-read GithubRepository $github
 * @property-read GithubComposerRepository $githubComposer
 * @property-read GithubReleaseRepository $githubRelease
 * @property-read TagRepository $tag
 * @method AbstractEntity persist(IEntity $entity, $withCascade = true)
 * @method AbstractRepository getRepositoryForEntity($entity)
 */
final class EntityModel extends NextrasModel
{

}
