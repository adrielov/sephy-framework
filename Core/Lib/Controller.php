<?php

namespace Core\Lib;

use Carbon\Carbon;
use Core\Application;
use Core\Config;
use Core\Helpers\Utils;
use Core\View;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response;

class Controller extends Application
{
    protected $blade;

    public $framework , $request , $response , $params;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->framework = (object) Config::get('framework');
        $this->request = (new Request());
        $this->response = (new Response());
    }

    /**
     * @return View
     */
    public function _404()
    {
        return $this->view('error.404');
    }

    /**
     * @param      $view
     * @param null $params
     *
     * @return View
     */
    public function view($view, $params = null)
    {
        $render = View::getInstance();

        $params['config'] = (new Config());
        $params['Date'] = (new Carbon());
        $params['Utils'] = (new Utils());

        $render->render($view, $params);

        return $render;
    }

    /**
     * @param      $content
     * @param null $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function send($content, $message = null)
    {
        $response = $this->response;
        if (is_null($content) || count($content) < 1) {
            $content['error'] = (isset($message)) ? $message : 'Nenhum resultado!';
        }

        $response->setContent(json_encode($content, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return    $response->send();
    }
}
