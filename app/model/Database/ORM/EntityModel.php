<?php

namespace App\Model\Database\ORM;

use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Bower\BowerRepository;
use App\Model\Database\ORM\Composer\ComposerRepository;
use App\Model\Database\ORM\Github\GithubRepository;
use App\Model\Database\ORM\GithubRelease\GithubReleaseRepository;
use App\Model\Database\ORM\Tag\TagRepository;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read AddonRepository $addon
 * @property-read BowerRepository $bower
 * @property-read ComposerRepository $composer
 * @property-read GithubRepository $github
 * @property-read GithubReleaseRepository $githubRelease
 * @property-read TagRepository $tag
 */
final class EntityModel extends NextrasModel
{

}
