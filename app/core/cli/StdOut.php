<?php

namespace App\Core\Cli;

final class StdOut
{

    /**
     * @param string $str
     */
    public function write($str)
    {
        fwrite(\STDOUT, $str);
    }
}
