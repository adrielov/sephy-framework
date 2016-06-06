<?php

namespace App\Middlewares;

use App\Middlewares\Contracts\IMiddleware;

abstract class BaseMiddleware implements IMiddleware
{
    protected $next;

    public function __construct($next)
    {
        if ($next instanceof IMiddleware) {
            $this->next = $next;
        }
    }

    public function handleNext()
    {
        if (!is_null($this->next)) {
            $this->next->handle();
        }
    }
}
