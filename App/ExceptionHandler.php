<?php
/**
 * Created by PhpStorm.
 * User: William
 * Date: 21/05/2016
 * Time: 11:59
 */

namespace App;

use Core\Exceptions\AuthException;
use Core\Exceptions\RoleException;
use Exception;

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
            die('Usuário deve estar logado');
        } else if ($this->exception instanceof RoleException) {
            die('Usuário não é do grupo' . $this->exception->getMessage());
        } else {
            echo $this->exception->getMessage();
        }
    }
}