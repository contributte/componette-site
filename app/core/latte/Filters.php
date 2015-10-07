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

        $delta = round($delta / 60);
        if ($delta < 1) return 'up-to-date';
        if ($delta < 10) return '< 10min';
        if ($delta < 90) return '< 1h';
        if ($delta < 2880) return '< 24h';
        if ($delta < 43200) return '< ' . round($delta / 1440) . 'd';
        if ($delta < 86400) return '< 30d';
        if ($delta < 525960) return '< ' . round($delta / 43200) * 30 . 'd';
        if ($delta < 1051920) return '< 1y';
        return '< ' . round($delta / 525960) . 'y';
    }

}
