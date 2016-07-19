<?php

namespace Core\Helpers;

use Carbon\Carbon;

class Utils
{
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function is_email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function textLimit($string, $size)
    {
        $string = (strlen($string) <= $size) ? $string : substr($string, 0, $size).'...';

        return $string;
    }

    public static function active($uri)
    {
        if (is_array($uri)) {
            $check = (in_array(str_replace('/', '.', substr($_SERVER['REQUEST_URI'], 1)), $uri));
        } else {
            $check = (str_replace('/', '.', substr($_SERVER['REQUEST_URI'], 1)) == $uri);
        }
        echo ($check) ? 'class="active"' : '';
    }

    public static function formatDate($key)
    {
        $createdAt = Carbon::parse($key);
        $now = $createdAt->format('d/m/Y');

        return $now;
    }

    public static function formatMoney($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
