<?php
namespace App\Middlewares\Contracts;

interface IMiddleware {
    public function __construct($next);
    public function handle();
}