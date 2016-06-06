<?php
error_reporting(E_ALL ^ E_NOTICE);

define("DS", DIRECTORY_SEPARATOR);
define("ROOT_DIR", realpath(dirname(__FILE__) . DS));
define("ROOT_APP", dirname(__FILE__) . DS . 'App' . DS);
define("ROOT_ASSETS", dirname(__FILE__) . DS . 'public/assets' . DS);
define("ROOT_CORE", dirname(__FILE__) . DS . 'Core' . DS);
define("ROOT_CACHE", dirname(__FILE__). DS . 'Cache' . DS);

date_default_timezone_set('America/Sao_Paulo');

if (file_exists($autoloader = 'vendor/autoload.php')) {
    
    require $autoloader;
    
    (new Core\Application())->run();

} else {
    
    echo "Autoloader não encontrado";

}
