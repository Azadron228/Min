<?php

namespace Min\Router;

use Min\Http\Request;
use Min\Middleware\MiddlewareHandler;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        return $this->addRoute('POST', $path, $callback);
    }

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => [],
        ];

        return $this;
    }

    public function middleware($middlewareClass)
    {
        MiddlewareHandler::handle($middlewareClass);
        $this->getLastRoute()['middleware'][] = $middlewareClass;
        return $this;
    }

    public function handleRequest()
    {
        $method = Request::method();
        $uri = Request::uri();

        foreach ($this->routes as $route) {
            if ($this->isMatchingRoute($route, $method, $uri)) {
                foreach ($route['middleware'] as $middlewareClass) {
                    MiddlewareHandler::handle($middlewareClass);
                }

                $params = $this->extractParams($route['path'], $uri);

                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $params);
                } else {
                    list($controllerClassName, $methodName) = $route['callback'];
                    $controller = new $controllerClassName();
                    call_user_func_array([$controller, $methodName], $params);
                }

                return;
            }
        }
        echo '404 Not Found';
    }

    private function isMatchingRoute($route, $method, $uri)
    {
        $pattern = str_replace('/', '\/', $route['path']);
        $pattern = preg_replace('/\{([a-zA-Z0-9]+)\}/', '([a-zA-Z0-9]+)', $pattern);

        return preg_match("/^{$pattern}$/", $uri) && $route['method'] == $method;
    }

    private function extractParams($pattern, $uri)
    {
        preg_match_all('/\{([a-zA-Z0-9]+)\}/', $pattern, $matches);
        $paramNames = $matches[1];

        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{([a-zA-Z0-9]+)\}/', '([a-zA-Z0-9]+)', $pattern);

        preg_match("/^{$pattern}$/", $uri, $values);
        array_shift($values);

        return array_combine($paramNames, $values);
    }

    private function getLastRoute()
    {
        return end($this->routes);
    }
}
