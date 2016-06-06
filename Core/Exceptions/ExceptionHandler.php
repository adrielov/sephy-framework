<?php

namespace Core\Exceptions;

use Exception;
use Invoker\Exception\NotCallableException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ExceptionHandler
{
    private $exception;

    public function __construct(Exception $e)
    {
        $this->exception = $e;
        $this->handle();
    }

    public function handle()
    {
        if ($this->exception instanceof AuthException) {
            die('User must be logged in');
        } elseif ($this->exception instanceof NotCallableException) {
            die('Method not found');
        }
        if ($this->exception instanceof MethodNotAllowedException) {
            die('Not allowed');
        } else {
            echo $this->exception->getMessage();
        }
    }
}
