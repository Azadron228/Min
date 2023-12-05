<?php

namespace Min\Router;

use Closure;

class Router
{
  private array $routes = [];
  protected array $middlewares = [];
  protected $router;

  public function route(
    string $path,
    string $method,
    string|array|Closure $callable,
    array $middleware = []
  ): Route {
    $route = new Route($path, $method, $callable, $middleware);
    $this->routes[] = $route;

    return $route;
  }

  public function middleware(array $middleware): void
  {
    echo "HUIUHIUHUHI";
    $this->middlewares = array_merge($this->middlewares, $middleware);
    var_dump($this->middlewares);
  }

  public function get(string $path, string|array|Closure $callable): Route
  {
    return $this->route($path, 'GET', $callable, $this->middlewares);
  }

  public function post(string $path, string|array|Closure $callable): Route
  {
    return $this->route($path, 'POST', $callable, $this->middlewares);
  }

  public function put(string $path, string|array|Closure $callable): Route
  {
    return $this->route($path, 'PUT', $callable, $this->middlewares);
  }

  public function delete(string $path, string|array|Closure $callable): Route
  {
    return $this->route($path, 'DELETE', $callable, $this->middlewares);
  }

  public function patch(string $path, string|array|Closure $callable): Route
  {
    return $this->route($path, 'PATCH', $callable, $this->middlewares);
  }

  public function dispatch()
  {
    $path = $_SERVER['REQUEST_URI'];
    $method = $_SERVER["REQUEST_METHOD"];

    // Find the matching route based on the request path and method
    $matchedRoute = $this->findMatchingRoute($path, $method);

    if ($matchedRoute) {
      $this->executeMiddleware($matchedRoute);
    } else {
      echo "404";
    }
  }

  public function findMatchingRoute(string $path, string $method): ?Route
  {
    foreach ($this->routes as $route) {
      var_dump($route);
        return $route;
    }

    return null;
  }

  public function matches(string $requestPath, string $requestMethod): bool
  {
    var_dump($requestMethod);
    var_dump($this->method);
    // Compare request method
    if ($requestPath !== $requestMethod) {
      return false;
    }

  }

  protected function executeMiddleware(Route $route)
  {
    // Execute global middlewares
    foreach ($this->middlewares as $middleware) {
      $middleware->handle();
    }

    // Execute middlewares specific to the matched route
    foreach ($route->getMiddleware() as $middleware) {
      $middleware->handle();
    }
  }
}

