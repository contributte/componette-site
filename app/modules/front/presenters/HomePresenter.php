<?php

namespace App\Modules\Front;

final class HomePresenter extends BasePresenter
{

    public function actionDefault()
    {
        $this->redirect('List:');
    }

}
