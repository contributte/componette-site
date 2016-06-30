<?php

use Tracy\Debugger;

/**
 * Dump;
 */
function d()
{
    foreach (func_get_args() as $var) {
        Debugger::dump($var);
    }
}

/**
 * Dump; die;
 */
function dd()
{
    foreach (func_get_args() as $var) {
        Debugger::dump($var);
    }

    die;
}

/**
 * Bar dump;
 */
function bd()
{
    foreach (func_get_args() as $var) {
        Debugger::barDump($var);
    }
}

/**
 * Bar dump; die;
 */
function bdd()
{
    foreach (func_get_args() as $var) {
        Debugger::barDump($var);
    }

    die;
}
