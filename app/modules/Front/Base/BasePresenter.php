<?php declare(strict_types = 1);

namespace App\Modules\Front\Base;

use App\Model\UI\AbstractPresenter;
use App\Model\Webpack\Entries;
use Contributte\Application\UI\Presenter\StructuredTemplates;
use Wavevision\NetteWebpack\InjectResolveEntryChunks;
use Wavevision\NetteWebpack\UI\Components\Assets\AssetsComponent;

/**
 * Base presenter for all front-end presenters.
 */
abstract class BasePresenter extends AbstractPresenter
{

	use AssetsComponent;
	use InjectResolveEntryChunks;
	use StructuredTemplates;

	protected function startup(): void
	{
		parent::startup();
		$this
			->getAssetsComponent()
			->setChunks($this->resolveEntryChunks->process(Entries::FRONT));
	}

}
