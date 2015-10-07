<?php

namespace App\Core\Cli;

final class Output
{

    /** @var StdOut */
    private $out;

    /** @var StdErr */
    private $err;

    /** @var StdBuffer */
    private $buffer;

    function __construct()
    {
        $this->out = new StdOut();
        $this->err = new StdOut();
        $this->buffer = new StdBuffer();
    }

    /**
     * OUTPUT ******************************************************************
     */

    /**
     * @param string $str
     */
    public function out($str)
    {
        $this->out->write($str);
    }

    /**
     * @param string $str
     */
    public function outln($str)
    {
        $this->out("$str\n");
    }

    /**
     * ERROR *******************************************************************
     */

    /**
     * @param string $str
     */
    public function err($str)
    {
        $this->err->write($str);
    }

    /**
     * @param string $str
     */
    public function errln($str)
    {
        $this->err("$str\n");
    }

    /**
     * BUFFER ******************************************************************
     */

    /**
     * @return StdBuffer
     */
    public function buffer()
    {
        return $this->buffer;
    }

}
