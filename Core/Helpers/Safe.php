<?php
/**
 * User: Sephy
 * Date: 06/06/2016
 * Time: 04:25.
 */
namespace Core\Helpers;

use Core\Config;

class Safe
{
    public static $salt;

    public function __construct()
    {
        self::$salt = Config::get('app.salt');
    }

    public static function hash($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function validate($string, $hash)
    {
        return password_verify($string, $hash);
    }
}
