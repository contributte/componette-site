<?php declare(strict_types = 1);

namespace App\Model\UI;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @property-read DefaultTemplate $template
 */
abstract class BaseControl extends Control
{

}
