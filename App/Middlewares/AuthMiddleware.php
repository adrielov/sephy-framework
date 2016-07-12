<?php

namespace App\Middlewares;

class AuthMiddleware extends BaseMiddleware
{
    /**
     * @throws \Exception
     */
    public function handle()
    {
        if ($logged = true) {
            throw new \Exception('Must be logged in');
        }

        return $this->handleNext();
    }
}
