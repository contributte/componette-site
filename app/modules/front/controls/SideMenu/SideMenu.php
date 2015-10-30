<?php

namespace App\Modules\Front\Controls\SideMenu;

use App\Model\ORM\TagsRepository;
use Nette\Application\UI\Control;

class SideMenu extends Control
{

    /** @var TagsRepository */
    private $tagsRepository;

    /**
     * @param TagsRepository $tagsRepository
     */
    public function __construct(TagsRepository $tagsRepository)
    {
        parent::__construct();
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->categories = $this->tagsRepository->findWithHighPriority();
        $this->template->tags = $this->tagsRepository->findWithLowPriority();

        $this->template->setFile(__DIR__ . '/templates/menu.latte');
        $this->template->render();
    }

}
