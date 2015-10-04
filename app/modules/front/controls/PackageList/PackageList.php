<?php

namespace App\Modules\Front\Controls\PackageList;

use App\Core\UI\BaseControl;
use App\Modules\Front\Controls\PackageMeta\IPackageMetaFactory;
use App\Modules\Front\Controls\PackageMeta\PackageMeta;
use Nextras\Orm\Collection\ICollection;

final class PackageList extends BaseControl
{

    /** @var ICollection */
    private $packages;

    /** @var IPackageMetaFactory */
    private $packageMetaFactory;

    /**
     * @param ICollection $packages
     * @param IPackageMetaFactory $packageMetaFactory
     */
    function __construct(
        ICollection $packages,
        IPackageMetaFactory $packageMetaFactory
    )
    {
        parent::__construct();
        $this->packages = $packages;
        $this->packageMetaFactory = $packageMetaFactory;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return PackageMeta
     */
    protected function createComponentMeta()
    {
        return $this->packageMetaFactory->create();
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->packages = $this->packages;

        $this->template->setFile(__DIR__ . '/templates/packages.latte');
        $this->template->render();
    }

}