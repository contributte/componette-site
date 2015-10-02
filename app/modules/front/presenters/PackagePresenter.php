<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Modules\Front\Controls\PackageDetail\IPackageDetailFactory;
use App\Modules\Front\Controls\PackageDetail\PackageDetail;

final class PackagePresenter extends BasePresenter
{

    /** @var PackagesRepository @inject */
    public $packagesRepository;

    /** @var IPackageDetailFactory @inject */
    public $packageDetailFactory;

    /** @var Package */
    protected $package;

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $this->package = $this->packagesRepository->getById($id);
        if (!$this->package) {
            $this->error('Package not found');
        }
    }

    /**
     * @param int $id
     */
    public function renderDetail($id)
    {
        $this->template->package = $this->package;
    }

    /**
     * @return PackageDetail
     */
    protected function createComponentPackage()
    {
        return $this->packageDetailFactory->create($this->package);
    }
}
