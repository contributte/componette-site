<?php

namespace App\Modules\Front\Api;

use App\Model\Facade\SearchFacade;

final class ApiPresenter extends BasePresenter
{

    /** @var SearchFacade @inject */
    public $searchFacade;

    /**
     * @param string $q
     */
    public function actionSuggest($q)
    {
        $output = [];
        $addons = $this->searchFacade
            ->findByQuery($q)
            ->orderBy('this->github->downloads', 'DESC');

        foreach ($addons as $addon) {
            $output[] = [
                'id' => $addon->id,
                'name' => $addon->github->name,
                'description' => $addon->github->description,
                'link' => $this->link(':Front:Addon:detail', $addon->id),
                'stars' => $addon->github->stars,
                'downloads' => $addon->github->downloads,
            ];
        }

        $this->sendJson($output);
    }
}
