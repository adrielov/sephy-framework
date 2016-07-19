<?php

namespace App\Controllers;

use Core\Lib\Controller;


class HomeController extends Controller
{
    public function index() {

        return $this->view('home.index');
    }

    public function subpage() {

        $this->params['title'] = 'Sephy - Simple PHP Framework - Sub Page';

        return $this->view('home.subpage', $this->params);
    }
}
