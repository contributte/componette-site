<?php

namespace App\Modules\Front;

use App\Model\ORM\PackagesRepository;

final class ApiPresenter extends BasePresenter
{

    /** @var PackagesRepository @inject */
    public $packagesRepository;

    /**
     * @param string $q
     */
    public function actionSuggest($q)
    {
        $output = [];
        $packages = $this->packagesRepository->search($q);
        foreach ($packages as $package) {
            $output[] = [
                'id' => $package->id,
                'value' => $package->metadata->name,
                'link' => $this->link(':Front:Package:detail', $package->id),
            ];
        }

        $this->sendJson($output);
    }
}
