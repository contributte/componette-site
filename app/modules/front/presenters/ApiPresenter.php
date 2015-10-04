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
        $packages = $this->packagesRepository
            ->search($q)
            ->orderBy('this->metadata->downloads', 'DESC');

        foreach ($packages as $package) {
            $output[] = [
                'id' => $package->id,
                'name' => $package->metadata->name,
                'description' => $package->metadata->description,
                'link' => $this->link(':Front:Package:detail', $package->id),
                'stars' => $package->metadata->stars,
                'downloads' => $package->metadata->downloads,
            ];
        }

        $this->sendJson($output);
    }
}
