<?php

namespace App\Modules\Front\Controls\SideMenu;

use App\Model\ORM\TagsRepository;
use Nette\Application\UI\Control;

class SideMenu extends Control
{

    /** @var TagsRepository */
    private $tagsRepository;


    public function __construct(TagsRepository $tagsRepository)
    {
        parent::__construct();
        $this->tagsRepository = $tagsRepository;
    }

    public function render()
    {
        $this->template->categories = $this->tagsRepository->findWithHighPriority();
        $this->template->tags = $this->tagsRepository->findWithLowPriority();
        $this->template->setFile(__DIR__ . '/templates/sideMenu.latte');
        $this->template->render();
    }
}
