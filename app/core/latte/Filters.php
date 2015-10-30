<?php

namespace App\Core\Latte;

use Nette\Utils\DateTime;

final class Filters
{

    /** @var string */
    public static $datetime = 'd.m.Y H:i';

    /**
     * @param mixed $count
     * @return mixed
     */
    public static function count($count)
    {
        if (is_numeric($count)) {
            return $count;
        } else {
            return 'N/A';
        }
    }

    /**
     * @param mixed $date
     * @return mixed
     */
    public static function datetime($date)
    {
        $date = self::time($date);
        if ($date === FALSE) return FALSE;

        return date(self::$datetime, $date);
    }

    /**
     * @param $time
     * @return mixed
     */
    public static function time($time)
    {
        if (!$time) {
            return FALSE;
        } elseif (is_numeric($time)) {
            $time = (int)$time;
        } elseif ($time instanceof DateTime) {
            $time = $time->format('U');
        } else {
            $time = strtotime($time);
        }

        return $time;
    }

    /**
     * @param mixed $time
     * @return int
     */
    public static function timeDelta($time)
    {
        $time = self::time($time);
        if ($time === FALSE) return FALSE;

        return time() - $time;
    }

    /**
     * @param mixed $time
     * @return string
     */
    public static function timeAgo($time)
    {
        $delta = self::timeDelta($time);
        if ($delta === FALSE) return 'N/A';
        if ($delta < 0) return 'N/A';

        $delta = round($delta / 60);
        if ($delta <= 60) return 'hot';
        if ($delta <= 1440) return '~ ' . ceil($delta / 60) . 'h';
        if ($delta < 525960) return '~ ' . ceil($delta / 1440) . 'd';

        // ------
        return '~ ' . round($delta / 525960, 1) . 'y';
    }

    /**
     * @param string $name
     * @return string
     */
    public static function shields($name)
    {
        $name = lcfirst($name);
        $name = preg_replace('#([A-Z]+)#', '-$1', $name);
        $name = str_replace('--', '-', $name);
        return $name;
    }

}
