<?php

namespace App\Core\Latte\Filters;

use Nette\Utils\Strings;

final class ComposerHelper
{

    /**
     * @param string $name
     * @return bool
     */
    public static function isPhpDependency($name)
    {
        $blacklist = [
            '^php$',
            '^ext-\w',
        ];

        foreach ($blacklist as $regex) {
            if (Strings::match($name, "#$regex#")) {
                return TRUE;
            }
        }

        return FALSE;
    }

}