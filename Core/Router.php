<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    public $routeColletion;
    private $prefix = '';
    private static $instance = null;
    private $middlewares = [];

    private function __construct()
    {
        $this->routeColletion = new RouteCollection();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $prefixParam
     * @param $callable
     *
     * @throws \ErrorException
     */
    public function prefix($prefixParam, $callable)
    {
        if (!is_callable($callable)) {
            throw new \ErrorException('O segundo parâmetro não é executável');
        }

        $oldPrefix = $this->prefix;
        $this->prefix .= $prefixParam;
        $callable($this);
        $this->prefix = $oldPrefix;
    }

    /**
     * @param       $path
     * @param       $controller
     * @param array $params
     */
    public function add($path, $controller, $params = [])
    {
        $this->setMethod(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'PATCH'], $path, $controller, $params);
    }

    /**
     * @param       $path
     * @param       $controller
     * @param array $params
     */
    public function get($path, $controller, $params = [])
    {
        $this->setMethod('GET', $path, $controller, $params);
    }

    /**
     * @param       $path
     * @param       $controller
     * @param array $params
     */
    public function post($path, $controller, $params = [])
    {
        $this->setMethod('POST', $path, $controller, $params);
    }

    /**
     * @param       $path
     * @param       $controller
     * @param array $params
     */
    public function put($path, $controller, $params = [])
    {
        $this->setMethod('PUT', $path, $controller, $params);
    }

    /**
     * @param       $httpMethod
     * @param       $path
     * @param       $controller
     * @param array $params
     */
    private function setMethod($httpMethod, $path, $controller, $params = [])
    {
        if (!is_array($httpMethod)) {
            $httpMethod = [$httpMethod];
        }

        list($controller, $method) = explode('::', $controller);
        $prefixedPath = $this->prefix.$path;

        $configsParams = array_merge([
            '_path'       => $path,
            '_controller' => $controller,
            '_method'     => $method,
        ], $params);

        if (is_array($this->middlewares) && count($this->middlewares) > 0) {
            $configsParams['_middleware'] = $this->middlewares;
        }

        $route = new Route($prefixedPath, $configsParams);
        $route->setMethods($httpMethod);
        $this->routeColletion->add(implode('', $httpMethod).$prefixedPath, $route);
    }

    /**
     * @return array
     */
    public static function getParams()
    {
        static::includeRoutes();

        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());
        $matcher = new UrlMatcher(self::getInstance()->routeColletion, $context);

        try {
            $parameters = $matcher->match(Request::createFromGlobals()->getPathInfo());
        } catch (ResourceNotFoundException $e) {
            $parameters = [
                '_path'       => $_SERVER['REQUEST_URI'],
                '_controller' => 'HomeController',
                '_method'     => '_404',
                '_route'      => 'GETPOSTPUTPATCHDELETEPATCH/',
            ];
        } catch (MethodNotAllowedException $e) {
            die('Not allowed');
        }

        return $parameters;
    }

    private static function includeRoutes()
    {
        static $included = false;
        if (!$included) {
            require ROOT_APP.'routes.php';
            $included = true;
        }
    }

    /**
     * @return bool
     */
    public static function init()
    {
        static $init = false;
        if (!$init) {
            self::includeRoutes();
            $init = true;
        }

        return $init;
    }

    /**
     * Return router collection.
     *
     * @return RouteCollection
     */
    public function getCollection()
    {
        return $this->routeColletion;
    }

    /**
     * @param $params
     * @param $closure
     */
    public function group($params, $closure)
    {
        $oldMiddlewares = $this->middlewares;
        $this->middlewares = array_merge($this->middlewares, isset($params['middleware']) ? $params['middleware'] : []);
        $closure($this);
        $this->middlewares = $oldMiddlewares;
    }
}

Router::init();
