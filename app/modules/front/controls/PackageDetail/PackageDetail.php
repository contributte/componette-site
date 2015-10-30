<?php

namespace App\Modules\Front\Controls\PackageDetail;

use App\Core\UI\BaseControl;
use App\Model\ORM\Package;
use App\Modules\Front\Controls\PackageMeta\IPackageMetaFactory;
use App\Modules\Front\Controls\PackageMeta\PackageMeta;

final class PackageDetail extends BaseControl
{

    /** @var Package */
    private $package;

    /** @var IPackageMetaFactory */
    private $packageMetaFactory;

    /**
     * @param Package $package
     * @param IPackageMetaFactory $packageMetaFactory
     */
    function __construct(
        Package $package,
        IPackageMetaFactory $packageMetaFactory
    )
    {
        parent::__construct();
        $this->package = $package;
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

    public function renderHeader()
    {
        $this->template->package = $this->package;
        $this->template->setFile(__DIR__ . '/templates/header.latte');
        $this->template->render();
    }

    public function renderContent()
    {
        $this->template->package = $this->package;
        $this->template->setFile(__DIR__ . '/templates/content.latte');
        $this->template->render();
    }

    public function renderSidebar()
    {
        $this->template->package = $this->package;
        $this->template->setFile(__DIR__ . '/templates/sidebar.latte');
        $this->template->render();
    }

}