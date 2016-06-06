<?php
use Core\Router;

$router = Router::getInstance();

$router->add('/', 'HomeController::index');
$router->add('/subpage', 'HomeController::subpage');
