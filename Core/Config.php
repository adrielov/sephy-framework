<?php

namespace Core;

class Config
{
    private static $instance;
    private $config_array;
    private $config_path;

    public function __construct()
    {
        $this->config_array = [];
        $this->config_path = ROOT_APP.'Config'.DS;
        $this->make();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = (new self());
        }

        return self::$instance;
    }

    public function set($config, $value = null)
    {
        if (is_array($config)) {
            foreach ($config as $key => $value) {
                $this->config_array[$key] = $value;
            }
        } else {
            $this->config_array[$config] = $value;
        }
    }

    public static function get($keys)
    {
        $keys = explode('.', $keys);
        $tmp = self::getInstance()->config_array;
        foreach ($keys as $key) {
            $tmp = isset($tmp[$key]) ? $tmp[$key] : null;
        }

        return $tmp;
    }

    public function make()
    {
        $configs = scandir($this->config_path);
        foreach ($configs as $config) {
            if (!in_array($config, ['.', '..'])) {
                $getFileConfig = $this->config_path.$config;
                if (file_exists($getFileConfig)) {
                    $getConfig = include $getFileConfig;
                    $this->config_array[str_replace('.php', '', $config)] = $getConfig;
                }
            }
        }

        return $this->config_array;
    }
}
