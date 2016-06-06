<?php

error_reporting(E_ALL);

if (version_compare($ver = PHP_VERSION, $req = '5.5.9', '<')) {
    exit(sprintf('You are running PHP %s, but Pagekit needs at least <strong>PHP %s</strong> to run.', $ver, $req));
}

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(realpath(dirname(__FILE__)).DS));
define('ROOT_APP', dirname(dirname(__FILE__)).DS.'App'.DS);
define('ROOT_ASSETS', dirname(__FILE__).DS.'assets'.DS);
define('ROOT_CORE', dirname(dirname(__FILE__)).DS.'Core'.DS);
define('ROOT_CACHE', dirname(dirname(__FILE__)).DS.'Cache'.DS);

date_default_timezone_set('America/Sao_Paulo');

if (file_exists($autoloader = '../vendor/autoload.php')) {
    require $autoloader;
    (new Core\Application())->run();
} else {
    echo 'Autoloader not found';
}
