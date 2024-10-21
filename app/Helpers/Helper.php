<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Config;

class Helper
{
    public static function arrayRemove(array &$array, $value)
    {
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        }
    }

    public static function getConfigValue($key)
    {
        return Config::get($key);
    }

    public static function setConfigValue($key, $value)
    {
        Config::set($key, $value);
    }

    public static function isDate($value, $default = false)
    {
        if(!is_string($value) || $value == '0000-00-00') return $default;
        $regexMatchDateFormat = '\d{1,4}[\/\-\\\]\d{1,2}[\/\-\\\]\d{1,4}';

        // make sure day & month not in wrong position
        $time = trim(preg_replace("/$regexMatchDateFormat/", '', $value));
        $date = trim(str_replace($time, '', $value));
        $parseDate = Helper::parseDate($date);
        $value = implode('-', array_values(array_filter($parseDate))) . ' ' .  $time;

        $timestamp = preg_match("/$regexMatchDateFormat/", $value) ? strtotime(preg_replace('/[\/\-\\\]/', '-', $value)) : $default;
        $date = \Carbon\Carbon::createFromTimestamp($timestamp);
        return $timestamp && $date->year > 0 ? $date  : $default;
    }
}
