<?php

namespace App\Modules\Front\Controls\SideMenu;


interface ISideMenuFactory
{
    /**
     * @return SideMenu
     */
    public function create();

}
