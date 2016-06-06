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

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = (new self());
        }

        return self::$instance;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->config_array[$key] = $value;
    }

    /**
     * @param $keys
     *
     * @return array|mixed|null
     */
    public static function get($keys)
    {
        $keys = explode('.', $keys);
        $tmp = self::getInstance()->config_array;
        foreach ($keys as $key) {
            $tmp = isset($tmp[$key]) ? $tmp[$key] : null;
        }

        return $tmp;
    }

    /**
     * @param $array
     */
    public function addConfigs($array)
    {
        foreach ($array as $key => $value) {
            $this->config_array[$key] = $value;
        }
    }

    /**
     * @return array
     */
    public function make()
    {
        $view = include $this->config_path.'views.php';
        $app = include $this->config_path.'app.php';
        $database = include $this->config_path.'database.php';
        $mail = include $this->config_path.'mail.php';
        $this->config_array['views'] = $view;
        $this->config_array['app'] = $app;
        $this->config_array['database'] = $database;
        $this->config_array['mail'] = $mail;

        return $this->config_array;
    }
}
