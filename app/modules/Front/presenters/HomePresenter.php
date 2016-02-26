<?php

namespace App\Modules\Front;

final class HomePresenter extends BasePresenter
{

    /**
     * Redirect to Portal
     */
    public function actionDefault()
    {
        $this->redirect(':Front:Portal:Home:');
    }

}
