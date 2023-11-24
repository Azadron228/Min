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

    public static function json()
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    public static function getallheaders()
    {
        $headers = getallheaders();
        return $headers;
    }
}
