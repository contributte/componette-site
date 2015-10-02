<?php

namespace App\Core\Http;

use Nette\Http\Url as NUrl;

final class Url extends NUrl
{

    /**
     * @param string $path
     * @return self
     */
    public function appendPath($path)
    {
        $this->setPath($this->getPath() . $path);
        return $this;
    }
}
