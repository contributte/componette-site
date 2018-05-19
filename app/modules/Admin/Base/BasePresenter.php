<?php declare(strict_types = 1);

namespace App\Modules\Admin\Base;

use App\Model\UI\AbstractPresenter;
use App\Model\UI\TUITemplate;

/**
 * Base presenter for all back-end presenters.
 */
abstract class BasePresenter extends AbstractPresenter
{

	use TUITemplate;

}
