<?php

namespace Min\Http;

class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
