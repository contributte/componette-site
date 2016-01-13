<?php

namespace App\Model\ORM;

use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Bower\BowerRepository;
use App\Model\ORM\Composer\ComposerRepository;
use App\Model\ORM\Github\GithubRepository;
use App\Model\ORM\Tag\TagRepository;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read AddonRepository $addon
 * @property-read BowerRepository $bower
 * @property-read ComposerRepository $composer
 * @property-read GithubRepository $github
 * @property-read TagRepository $tag
 */
final class Model extends NextrasModel
{
}
