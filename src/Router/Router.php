<?php

namespace Min\Router;

use Closure;
use Min\Router\Route;

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

  public function get(string $path, string|array|Closure $callable, array $middlewares = null): self
  {
    $middlewares = $middlewares ?? [];
    $this->route($path, 'GET', $callable, $middlewares);
    return $this;
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
      if ($this->matches($route, $path, $method)) {
        return $route;
      }
    }

    return null;
  }

  public function matches(Route $route, string $requestPath, string $requestMethod): bool
  {
    return $route->getPath() === $requestPath && $route->getMethod() === $requestMethod;
  }

  protected function executeMiddleware(Route $route)
  {
    var_dump($route);
    $middlewares = $route->getMiddleware();
    foreach ($middlewares as $middlewareClass) {
      $middleware = new $middlewareClass();
      $middleware->handle();
    }
  }
}
