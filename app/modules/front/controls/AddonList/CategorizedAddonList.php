<?php

namespace App\Modules\Front\Controls\AddonList;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Tag\Tag;
use App\Modules\Front\Controls\AddonMeta\AddonMeta;
use Nextras\Orm\Collection\ICollection;

final class CategorizedAddonList extends AddonList
{

    /** @var ICollection|Tag[] */
    private $categories;

    /**
     * @param ICollection $addons
     * @param ICollection $categories
     */
    public function __construct($addons, $categories)
    {
        parent::__construct($addons);
        $this->categories = $categories;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return AddonMeta
     */
    protected function createComponentMeta()
    {
        return new AddonMeta();
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        /** @var Tag[] $categories */
        $categories = $this->categories
            ->orderBy(['priority' => 'DESC', 'name' => 'ASC'])
            ->fetchPairs('id');

        /** @var Addon[] $addons */
        $addons = $this->addons
            ->orderBy(['owner' => 'ASC', 'name' => 'ASC']);

        // Arrange addons
        $tmplist = [];
        foreach ($addons as $addon) {
            if ($addon->tags->countStored() > 0) {
                foreach ($addon->tags as $tag) {
                    if (!isset($tmplist[$tag->id])) $tmplist[$tag->id] = [];
                    $tmplist[$tag->id][] = $addon;
                }
            } else {
                if (!isset($tmplist[-1])) $tmplist[-1] = [];
                $tmplist[-1][] = $addon;
            }
        }

        // Sort addons by categeries priority
        $list = [];
        foreach ($categories as $category) {
            if (isset($tmplist[$category->id])) {
                $list[$category->id] = $tmplist[$category->id];
            }
        }

        // Append no tags addons
        if (isset($tmplist[-1])) {
            $list[-1] = $tmplist[-1];
            $categories[-1] = (object) ['id' => -1, 'name' => 'other'];
        }

        // Fill template
        $this->template->categories = $categories;
        $this->template->list = $list;

        // Render
        $this->template->setFile(__DIR__ . '/templates/categorized.list.latte');
        $this->template->render();
    }

}
