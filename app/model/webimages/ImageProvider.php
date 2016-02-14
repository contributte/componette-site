<?php

namespace App\Model\WebImages;

interface ImageProvider
{

    /**
     * @param array $args
     */
    public function create(array $args);

}
