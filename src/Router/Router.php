
<?php

namespace Min\Router;

use Min\Middleware\MiddlewareInterface;

class Router
{
  private array $routes = [];
  protected array $middlewares = [];

  public function __construct(
    protected RouterInterface $router,
    protected bool $detectDuplicates = true
  ) {
  }

  public function route(
    string $path,
    MiddlewareInterface $middleware,
    string $method = null
  ): Route {
    $route = new Route($path, $middleware[], $method);
    $this->routes[] = $route;
    $this->router->addRoute($route);

    return $route;
  }


  public function middleware(array $middleware): self
  {
    $this->middlewares[] = $middleware;
    return $this;
  }

  public function get(string $path, $middleware): Route
  {
    return $this->route($path, $middleware, 'GET');
  }

  public function post(string $path, MiddlewareInterface $middleware): Route
  {
    return $this->route($path, $middleware, 'POST');
  }

  public function put(string $path, MiddlewareInterface $middleware): Route
  {
    return $this->route($path, $middleware, 'PUT');
  }

  public function delete(string $path, MiddlewareInterface $middleware): Route
  {
    return $this->route($path, $middleware, 'DELETE');
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

  protected function findMatchingRoute(string $path, string $method): ?Route
  {
    foreach ($this->routes as $route) {
      if ($route->matches($path, $method)) {
        return $route;
      }
    }

    return null;
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

    $route->handle();
  }
}
