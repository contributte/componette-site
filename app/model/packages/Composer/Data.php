<?php

namespace App\Model\Packages\Composer;

use App\Core\Http\Url;

final class Data
{

    /** @var array */
    private $data = [];

    /** @var array */
    private $cached = [];

    /**
     * @param mixed $data
     */
    function __construct($data)
    {
        $this->data = json_decode($data, TRUE);
    }

    /**
     * @return string
     */
    public function getPackageUrl()
    {
        if (!isset($this->cached['package']['name'])) {
            if ($this->data && isset($this->data['package']['name'])) {
                $url = new Url('https://packagist.org/packages');
                $url->appendPath('/');
                $url->appendPath($this->data['package']['name']);

                $this->cached['package']['name'] = $url;
            } else {
                $this->cached['package']['name'] = '#';
            }
        }

        return $this->cached['package']['name'];
    }


}