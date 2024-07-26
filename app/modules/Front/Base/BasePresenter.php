<?php declare(strict_types = 1);

namespace App\Modules\Front\Base;

use App\Model\Services\Search\Search;
use App\Model\UI\AbstractPresenter;
use App\Model\UI\Destination;
use App\Model\Webpack\Entries;
use App\Modules\Front\Base\Controls\FlashMessages\FlashMessagesComponent;
use App\Modules\Front\Base\Controls\Layout\Footer\FooterComponent;
use App\Modules\Front\Base\Controls\Layout\Header\HeaderComponent;
use App\Modules\Front\Base\Controls\Search\ISearchFactory;
use App\Modules\Front\Base\Controls\Search\Search as SearchComponent;
use Contributte\Application\UI\Presenter\StructuredTemplates;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * Base presenter for all front-end presenters.
 *
 * @property-read DefaultTemplate $template
 */
abstract class BasePresenter extends AbstractPresenter
{

	use FlashMessagesComponent;
	use FooterComponent;
	use HeaderComponent;
	use StructuredTemplates;

	/** @inject */
	public Search $search;

	/** @inject */
	public ISearchFactory $searchFactory;

	protected function createComponentSearch(): SearchComponent
	{
		$search = $this->searchFactory->create();

		$search['form']->setMethod('GET');
		$search['form']->setAction($this->link(Destination::FRONT_SEARCH));

		$search['form']['q']
			->controlPrototype
			->data('handle', $this->link(Destination::FRONT_SEARCH, ['q' => '_QUERY_']));

		return $search;
	}

}
