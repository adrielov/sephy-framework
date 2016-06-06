<?php

namespace Core;

use Core\Exceptions\ExceptionHandler;
use Exception;
use Illuminate\Cache\CacheManager as CacheManager;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Symfony\Component\HttpFoundation\Session\Session;

class Application
{
    public $capsuleDb = [];
    public $cache , $session;
    private static $instance;

    public function __construct()
    {
        $this->capsuleDb = new Capsule();
        $this->capsuleDb->addConnection(Config::get('database.providers.pdo'));
        $this->capsuleDb->setEventDispatcher(new Dispatcher(new Container()));
        $this->session = new Session();
        $this->capsuleDb->bootEloquent();
        $this->capsuleDb->setAsGlobal();
        $this->db = $this->capsuleDb->getConnection();
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
     * @return CacheManager
     */
    public function cache()
    {
        return $this->cache;
    }

    /**
     * @return Session
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function redirect($url)
    {
        $url = str_replace('.', DS, $url);

        return header('location: /'.$url);
    }

    /**
     * @return bool|\ReflectionMethod
     */
    public function run()
    {
        try {
            date_default_timezone_set(Config::get('app.timezone'));
            static::stripTraillingSlash();

            $this->session()->start();

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

            if ($routerParams === false) {
                $routerParams = ['_controller' => 'Controller', '_method' => '_404', '_params' => []];
                $controllerFullName = '\\Core\\Lib\\'.$routerParams['_controller'];
            } else {
                $controllerFullName = '\\App\\Controllers\\'.$routerParams['_controller'];
            }


            if (!method_exists($controllerFullName, $routerParams['_method'])) {
                die("Method {$routerParams['_method']} not found");
            }

            $reflectionMethod = new \ReflectionMethod($controllerFullName, $routerParams['_method']);
            $reflectionMethod->invokeArgs(new $controllerFullName(), $controllerParams);

            return $reflectionMethod;
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
                $configMiddleware = Config::get('middleware');
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
