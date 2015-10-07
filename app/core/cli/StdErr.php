<?php

namespace App\Core\Cli;

final class StdErr
{

    /**
     * @param string $str
     */
    public function write($str)
    {
        fwrite(\STDERR, $str);
    }
}