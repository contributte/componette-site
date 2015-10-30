<?php

namespace App\Model\ORM;

use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read PackagesRepository $packages
 * @property-read MetadatasRepository $metadata
 * @property-read TagsRepository $tags
 */
final class Model extends NextrasModel
{
}
