<?php

namespace App\Model\Addons;

use App\Core\Http\Url;

final class ExtraComposer
{

    /** @var array */
    private $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string|NULL $package
     * @return string
     */
    public function getPackageUrl($package = NULL)
    {
        $url = new Url('https://packagist.org/packages');
        $url->appendPath('/');

        if ($package) {
            $url->appendPath($package);
        } else {
            $url->appendPath(isset($this->data['name']) ? $this->data['name'] : '#');
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return isset($this->data['name']) ? $this->data['name'] : NULL;
    }

    /**
     * @return string
     */
    public function getRequire()
    {
        return isset($this->data['require']) ? $this->data['require'] : NULL;
    }

    /**
     * @return string
     */
    public function getRequireDev()
    {
        return $this->data['require-dev'];
    }

}
