<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use Nextras\Orm\Collection\ICollection;

final class ListPresenter extends BasePackagesPresenter
{

    /** @var ICollection|Package[] */
    private $packages;

    /**
     * DEFAULT *****************************************************************
     */

    public function actionDefault()
    {
        $this->packages = $this->packagesFacade->findAll();
    }

    /**
     * BY OTHER ****************************************************************
     */

    /**
     * @param string $slug
     */
    public function actionOwner($slug)
    {
        $this->packages = $this->packagesFacade->findByOwner($slug);
    }

    /**
     * @param string $slug
     */
    public function renderOwner($slug)
    {
        $this->template->owner = $slug;
    }

    /**
     * BY SEARCH ***************************************************************
     */

    /**
     * @param string $q
     */
    public function actionSearch($q)
    {
        $this->packages = $this->packagesFacade->findByQuery($q);
    }

    /**
     * BY TAG *****************************************************************
     */

    /**
     * @param string $tag
     */
    public function actionTag($tag)
    {
        $this->packages = $this->packagesFacade->findByTag($tag);
    }


    /**
     * @param string $tag
     */
    public function renderTag($tag)
    {
        $this->template->tag = $tag;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return Controls\PackageList\PackageList
     */
    protected function createComponentPackages()
    {
        return $this->createPackagesControl($this->packages);
    }
}
