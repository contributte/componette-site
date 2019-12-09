<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Search;

use App\Model\Services\Search\Search as Searching;
use App\Model\UI\BaseControl;
use Nette\Application\UI\Form;

final class Search extends BaseControl
{

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

	/**
	 * FORMS *******************************************************************
	 */

	protected function createComponentForm(): Form
	{
		$form = new Form();

		$form->addText('q')
			->setDefaultValue($this->search->q);

		$form->onSuccess[] = function (Form $form): void {
			$this->search->q = $form->values->q;
			$this->onSearch($form->values->q);
		};

		return $form;
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/search.latte');
		$this->template->render();
	}

}
