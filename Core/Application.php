<?php

namespace Core;

use Core\Exceptions\ExceptionHandler;
use Core\Lib\Session;
use DI\ContainerBuilder;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class Application
{
    public $capsuleDb = [];
    public $session , $di;
    private static $instance;

    public function __construct()
    {
        $di_builder = new ContainerBuilder();
        $di_builder->useAutowiring(true);
        $this->di = $di_builder->build();
        $this->session = new Session();
        try {
            $this->capsuleDb = new Capsule();
            $this->capsuleDb->addConnection(Config::get('database.providers.pdo'));
            $this->capsuleDb->setEventDispatcher(new Dispatcher(new Container()));
            $this->capsuleDb->bootEloquent();
            $this->capsuleDb->setAsGlobal();
            $this->db = $this->capsuleDb->getConnection();
        }catch (Exception $e) {
			new ExceptionHandler($e);
		}
    }

    /**
     * @return Application
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
     * @param $url
     */
    public function redirect($url)
    {
        return header('location: /'.str_replace('.', DS, $url));
    }

    /**
     * @return bool|mixed
     */
    public function run()
    {
        try {
            date_default_timezone_set(Config::get('app.timezone'));

            static::stripTraillingSlash();


            $this->session->start();

            Router::init();

            $routerParams = Router::getParams();

            $this->handleMiddlewares($routerParams);

            $controllerParams = [];
            foreach ($routerParams as $paramName => $paramValue) {
                if (substr($paramName, 0, 1) != '_') {
                    $controllerParams[$paramName] = $paramValue;
                }
            }

            if (isset($routerParams['_params']) && is_array($routerParams['_params'])) {
                foreach ($routerParams['_params'] as $paramName => $paramValue) {
                    $controllerParams[$paramName] = $paramValue;
                }
            }
            $controllerFullName = '\\App\\Controllers\\'.$routerParams['_controller'];
            try {
                return $this->di->call($controllerFullName.'::'.$routerParams['_method'], $controllerParams);
            } catch (Exception $e) {
                new ExceptionHandler($e);
            }
        } catch (Exception $e) {
            new ExceptionHandler($e);
        }

        return true;
    }

    /**
     * @param $routerParams
     */
    private function handleMiddlewares($routerParams)
    {
        if (array_key_exists('_middleware', $routerParams)) {
            $middlewaresList = [];
            foreach ($routerParams['_middleware'] as $middlewareGroup) {
                $configMiddleware = Config::get('middlewares');
                if (isset($configMiddleware[$middlewareGroup])) {
                    $middlewaresList = array_merge($middlewaresList, $configMiddleware[$middlewareGroup]);
                }
            }

            $middlewaresList = array_reverse($middlewaresList);
            $middlewareObjects = [];
            $lastMiddleware = null;

            foreach ($middlewaresList as $middleware) {
                $md = new $middleware($lastMiddleware);
                $middlewareObjects[] = $md;
                $lastMiddleware = $md;
            }

            $middlewareObjects = array_reverse($middlewareObjects);
            if (isset($middlewareObjects[0])) {
                $middlewareObjects[0]->handle();
            }
        }
    }

    public static function stripTraillingSlash()
    {
        $urlParts = explode('?', $_SERVER['REQUEST_URI']);
        if ($urlParts[0] != '/') {
            $urlParts[0] = substr($urlParts[0], -1, 1) == '/' ? substr($urlParts[0], 0, -1) : $urlParts[0];
            $_SERVER['REQUEST_URI'] = implode('?', $urlParts);
        }
    }
}
