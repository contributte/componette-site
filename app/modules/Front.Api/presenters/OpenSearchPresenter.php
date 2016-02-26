<?php

namespace App\Modules\Front\Api;

use App\Model\Facade\SearchFacade;

final class OpenSearchPresenter extends BasePresenter
{

    /** @var SearchFacade @inject */
    public $searchFacade;

    /**
     * @param string $q
     * @return void
     * @send json
     */
    public function actionSuggest($q)
    {
        $addons = $this->searchFacade->findByQuery($q);

        $output = [];
        $terms = [];
        foreach ($addons as $addon) {
            $terms[] = [
                'completion' => $addon->fullname,
                'description' => $addon->github->description,
                'link' => $this->link(':Front:Portal:Addon:detail', $addon->id),
            ];
        }

        // 1st -> query string
        // 2nd -> completions
        // 3rd -> descriptions
        // 4th -> links
        $output[0] = $q;
        $output[1] = [];
        $output[2] = [];
        $output[3] = [];
        foreach ($terms as $term) {
            $output[1][] = $term['completion'];
            $output[2][] = $term['description'];
            $output[3][] = $term['link'];
        }

        $this->sendJson($output);
    }
}
