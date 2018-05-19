<?php declare(strict_types = 1);

namespace App\Modules\Front\Base;

use App\Model\UI\AbstractPresenter;
use App\Model\UI\TUITemplate;

/**
 * Base presenter for all front-end presenters.
 */
abstract class BasePresenter extends AbstractPresenter
{

	use TUITemplate;

}
