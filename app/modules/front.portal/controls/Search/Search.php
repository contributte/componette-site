<?php

namespace App\Modules\Front\Portal\Controls\Search;

use App\Core\UI\BaseControl;
use App\Model\Search\Search as Searching;
use Nette\Application\UI\Form;

final class Search extends BaseControl
{

    /** @var array */
    public $onSearch = [];

    /** @var Searching */
    private $search;

    /**
     * @param Searching $search
     */
    public function __construct(Searching $search)
    {
        parent::__construct();
        $this->search = $search;
    }

    /**
     * @param array $params
     */
    public function loadState(array $params)
    {
        if (!isset($params['by'])) {
            $params['by'] = $this->search->by;
        }

        parent::loadState($params);
    }

    /**
     * FORMS *******************************************************************
     */

    /**
     * @return Form
     */
    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText('q')
            ->setDefaultValue($this->search->q);

        $form->onSuccess[] = function (Form $form) {
            $this->search->q = $form->values->q;
            $this->onSearch($form->values->q);
        };

        return $form;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/search.latte');
        $this->template->render();
    }

    public function renderControls()
    {
        $this->template->search = $this->search;
        $this->template->setFile(__DIR__ . '/templates/controls.latte');
        $this->template->render();
    }

}
