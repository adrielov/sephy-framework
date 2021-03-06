<?php
/**
 * User: Sephy
 * Date: 19/05/2016
 * Time: 01:06.
 */
namespace Core\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB extends Capsule
{
    public static function __callStatic($function, $arguments)
    {
        return call_user_func_array([static::instance(), $function], $arguments);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = (new Capsule());
        }

        return self::$instance;
    }
}
