<?php
/**
 * User: Sephy
 * Date: 06/06/2016
 * Time: 02:04.
 */
namespace Core;

use Core\ViewRenderes\BladeRenderer;
use Core\ViewRenderes\TwigRenderer;

class View
{
    private $renderer;
    private $template;
    private $params = [];

    private static $intance;

    public static function getInstance()
    {
        if (!self::$intance) {
            self::$intance = (new self());
        }

        return self::$intance;
    }

    public function __construct()
    {
        $config = Config::getInstance();
        $view_engine = $config->get('views.engine');
        $view_folder = $config->get('views.path_views');
        $view_cache = $config->get('views.path_cache');

        $paths = new \SplPriorityQueue();
        $paths->insert($view_folder, 100);

        switch ($view_engine) {
            case 'blade':
                $this->renderer = new BladeRenderer($paths, ['cache_path' => $view_cache]);
                break;
            case 'twig':
                $this->renderer = new TwigRenderer($paths);
                break;
            default:
                $this->renderer = new BladeRenderer($paths, $view_cache);
                break;
        }
    }

    /**
     * Render the view specified.
     *
     * @param string $view
     */
    public static function render($view = null, $params = null)
    {
        $params = ($params) ? $params : self::getInstance()->params;
        $view = $view ?: self::getInstance()->template;

        echo self::getInstance()->renderer->render($view, $params);
    }

    /**
     * Set the view file to be rendered.
     *
     * @param string $template
     */
    public function setView($template)
    {
        $this->template = $template;
    }

    /**
     * Adds a parameter in the view.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Add array parameters in view, each position of the array will become a variable
     * available to the view.
     *
     * @param array $array
     */
    public function addParams($array)
    {
        foreach ($array as $name => $value) {
            $this->addParam($name, $value);
        }
    }

    /**
     * Returns the specified parameter.
     *
     * @param string $name
     *
     * @return void|mixed
     */
    public function getParam($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }

        return true;
    }
}
