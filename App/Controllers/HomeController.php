<?php

namespace App\Controllers;

use Core\Lib\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->params['title'] = 'Sephy Simple PHP Framework';
        $this->view('home.index', $this->params);
    }

    public function subpage()
    {
        $this->params['title'] = 'Sephy Simple PHP Framework - Sub Page';
        $this->view('home.subpage', $this->params);
    }
}
