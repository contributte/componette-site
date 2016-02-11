<?php

namespace App\Modules\Front\Controls\SideMenu;

use App\Model\ORM\Tag\Tag;
use App\Model\ORM\Tag\TagRepository;
use Nette\Application\UI\Control;
use Nextras\Orm\Collection\ICollection;

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
     * @param ICollection|Tag[] $tags
     * @return Tag[]
     */
    private function ensure(ICollection $tags)
    {
        $ensured = [];
        foreach ($tags as $tag) {
            if ($tag->addons->countStored() > 0) {
                $ensured[] = $tag;
            }
        }

        return $ensured;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->categories = $this->tagRepository->findWithHighPriority();
        $this->template->tags = $this->ensure($this->tagRepository->findWithLowPriority());

        $this->template->setFile(__DIR__ . '/templates/menu.latte');
        $this->template->render();
    }

}
