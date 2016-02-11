<?php

namespace App\Core\Utils;

use Nette\Utils\Strings;
use Nette\Utils\Validators as NValidators;

final class Validators extends NValidators
{

    /**
     * @param string $url
     * @return bool
     */
    public static function isUrlFragment($url)
    {
        return Strings::startsWith($url, '#');
    }

}
