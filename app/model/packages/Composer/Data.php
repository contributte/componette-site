<?php

namespace App\Model\Packages\Composer;

use App\Core\Http\Url;

final class Data
{

    /** @var array */
    private $data = [];

    /**
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getPackageUrl()
    {
        $url = new Url('https://packagist.org/packages');
        $url->appendPath('/');
        $url->appendPath(isset($this->data['name']) ? $this->data['name'] : '#');
        return $url;
    }

}
