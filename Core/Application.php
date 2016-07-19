<?php

namespace Core;

use Core\Database\DB;
use Core\Exceptions\ExceptionHandler;
use Core\Lib\Request;
use Core\Lib\Session;
use DI\ContainerBuilder;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Response;

class Application
{
    protected $di_builder;
    private static $instance;
    public $capsuleDb = [];
    public $session;
    public $di;
    public $request;
    public $config;
    public $response;
    public $params;

    public function __construct()
    {
        $this->session = (new Session());
        $this->request = (new Request());
        $this->config = (new Config());
        $this->response = (new Response());
        $this->di_builder = (new ContainerBuilder());

        $this->di_builder->useAutowiring(true);
        $this->di = $this->di_builder->build();

        try {
            $this->capsuleDb = new DB();
            $this->capsuleDb->addConnection(Config::get('database.providers.pdo'));
            $this->capsuleDb->setEventDispatcher(new Dispatcher(new Container()));
            $this->capsuleDb->bootEloquent();
            $this->capsuleDb->setAsGlobal();
            $this->db = $this->capsuleDb->getConnection();
        } catch (Exception $e) {
            new ExceptionHandler($e);
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function run()
    {
        error_reporting((Config::get('app.debug')) ? E_ALL : 0);
        //Set Date Timezone
        date_default_timezone_set(Config::get('app.timezone'));

        //Set Cache Ctatus
        ini_set('opcache.revalidate_freq', (Config::get('app.cache')) ? '0' : '1');

        try {
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

    private function handleMiddlewares($routerParams)
    {
        if (array_key_exists('_middleware', $routerParams)) {
            $middlewaresList = [];
            $configMiddleware = Config::get('middlewares');
            foreach ($routerParams['_middleware'] as $middlewareGroup) {
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
