<?php

namespace App\Model\Templating\Filters;

use Nette\Utils\DateTime;
use Nette\Utils\Strings;

final class Filters
{

	private const EMOJI_CND = 'https://cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/images/basic/%s.png';

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
	 * @param mixed $time
	 * @return mixed
	 */
	public static function time($time)
	{
		if (!$time) {
			return FALSE;
		} elseif (is_numeric($time)) {
			$time = (int) $time;
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
		if ($time === FALSE) return 0;

		return time() - $time;
	}

	/**
	 * @param mixed $time
	 * @return string
	 */
	public static function timeAgo($time)
	{
		$delta = self::timeDelta($time);
		if ($delta === 0) return 'N/A';
		if ($delta < 0) return 'N/A';

		$delta = round($delta / 60);
		if ($delta <= 60) return 'hot';
		if ($delta <= 1440) return ceil($delta / 60) . 'h';
		if ($delta < 525960) return ceil($delta / 1440) . 'd';

		return round($delta / 525960, 1) . 'y';
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public static function shields($name)
	{
		$name = lcfirst($name);
		$name = preg_replace('#([A-Z]+)#', '-$1', $name);
		assert($name !== null); // impossible to have null here, regex is valid
		$name = str_replace('--', '-', $name);

		return $name;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public static function ucfirst($str)
	{
		return ucfirst($str);
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public static function emojify($str): string
	{
		return Strings::replace(htmlspecialchars($str), '#:([a-z0-9+-_]+):#', function (array $emoji) {
			return sprintf('<img class="emoji" src="%s" title="%s">', sprintf(self::EMOJI_CND, $emoji[1]), $emoji[1]);
		});
	}

}
