<?php

namespace Core;

abstract class Config {

	/**
     * @param      $name
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get($name, $default = null) {
        if ($name == '.') {
            return $default;
        }
        
        $config = require ROOT_APP . 'config.php';
        $root = $config;
        
        $configPath = explode('.', $name);
        
        foreach ($configPath as $key) {
            if (array_key_exists($key, $root)) {
                $root = $root[$key];
            } else {
                return $default;
            }
        }
        
        return $root;
    }
}