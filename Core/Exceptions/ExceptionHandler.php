<?php

namespace Core\Exceptions;

use Core\Error;
use Exception;
use Invoker\Exception\NotCallableException;
use PDOException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ExceptionHandler
{
    public $exception;

    public function __construct(Exception $exc)
    {
        $this->exception = $exc;
        $this->handle();
    }

    public function handle()
    {
        if ($this->exception instanceof AuthException) {
            Error::log($this->exception);
        } elseif ($this->exception instanceof NotCallableException) {
            Error::log($this->exception);
        } elseif ($this->exception instanceof PDOException) {
            Error::log($this->exception);
        } elseif ($this->exception instanceof MethodNotAllowedException) {
            Error::log($this->exception);
        }
    }
}
