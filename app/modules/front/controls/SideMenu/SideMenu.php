<?php

namespace App\Modules\Front\Controls\SideMenu;

use App\Model\ORM\Tag\TagRepository;
use Nette\Application\UI\Control;

final class SideMenu extends Control
{

    /** @var TagRepository */
    private $tagRepository;

    /**
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        parent::__construct();
        $this->tagRepository = $tagRepository;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->categories = $this->tagRepository->findWithHighPriority();
        $this->template->tags = $this->tagRepository->findWithLowPriority();

        $this->template->setFile(__DIR__ . '/templates/menu.latte');
        $this->template->render();
    }

}
