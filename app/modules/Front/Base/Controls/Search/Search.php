<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Search;

use App\Model\Services\Search\Search as Searching;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use Nette\Application\UI\Form;

final class Search extends BaseControl
{

	use SvgComponent;

	/** @var callable[] */
	public $onSearch = [];

	/** @var Searching */
	private $search;

	public function __construct(Searching $search)
	{
		$this->search = $search;
	}

	/**
	 * @param string[] $params
	 */
	public function loadState(array $params): void
	{
		if (!isset($params['by'])) {
			$params['by'] = $this->search->by;
		}

		parent::loadState($params);
	}

	protected function createComponentForm(): Form
	{
		$form = new Form();

		$form->addText('q')
			->setDefaultValue($this->search->q);
		$form->addSubmit('submit');

		$form->onSuccess[] = function (Form $form): void {
			$this->search->q = $form->values->q;
			$this->onSearch($form->values->q);
		};

		return $form;
	}

	public function render(): void
	{
		$this->template->setParameters([
			'searchIcon' => [
				'className' => 'h-5 md:mr-4',
				'fill' => '8A99B0',
				'image' => 'search-line',
				'size' => 64,
				'type' => 'system',
			]]);
		$this->template->setFile(__DIR__ . '/templates/search.latte');
		$this->template->render();
	}

}
