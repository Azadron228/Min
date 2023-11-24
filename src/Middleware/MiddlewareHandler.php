<?php

namespace Min\Middleware;

class MiddlewareHandler
{
    public static function handle($middlewareClass)
    {
        $middleware = new $middlewareClass();
        $middleware->handle();
    }
}
