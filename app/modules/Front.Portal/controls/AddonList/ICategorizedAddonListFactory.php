<?php

namespace App\Modules\Front\Portal\Controls\AddonList;

use Nextras\Orm\Collection\ICollection;

interface ICategorizedAddonListFactory
{

    /**
     * @param ICollection $addons
     * @param ICollection $categories
     * @return CategorizedAddonList
     */
    public function create($addons, $categories);

}
