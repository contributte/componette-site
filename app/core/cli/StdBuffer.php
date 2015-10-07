<?php

namespace App\Core\Cli;

final class StdBuffer
{

    /** @var string */
    protected $str;

    /**
     * @param string $str
     */
    public function add($str)
    {
        $this->str .= $str;
    }

    /**
     * @param string $str
     */
    public function flush()
    {
        $tmp = $this->str;
        $this->clean();
        return $tmp;
    }

    public function clean()
    {
        $this->str = NULL;
    }
}