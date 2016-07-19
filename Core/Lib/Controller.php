<?php

namespace Core\Lib;

use Carbon\Carbon;
use Core\Application;
use Core\Config;
use Core\Helpers\Utils;
use Core\View;

class Controller extends Application
{
    public function __construct()
    {
        parent::__construct();
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
        $params['Config']   = (new Config);
        $params['Date']     = (new Carbon);
        $params['Utils']    = (new Utils);

        View::render($view, $params);
    }

	/**
	 * @param      $content
	 * @param null $message
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function send($content, $message = null)
    {
        if(is_null($content) || count($content) == 0)
            $content['error'] = (isset($message))?$message:"Nenhum resultado!";

        $this->response->setContent(json_encode($content,JSON_PRETTY_PRINT,JSON_UNESCAPED_UNICODE));
        $this->response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return 	$this->response->send();
    }
}
