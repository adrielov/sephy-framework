<?php

use Core\Router;

$router = Router::getInstance();

$router->add('/', 'HomeController::index');

$router->add('/subpage', 'HomeController::subpage');

$router->group(['middleware' => ['auth']], function (Core\Router $router) {

	$router->add('/middleware', 'HomeController::subpage');

});

//	$router->add('/profile/{id}', 'UserController::profile',[
//		'id' => '[0-9]'
//	]);