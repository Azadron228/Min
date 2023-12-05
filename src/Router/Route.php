<?php

namespace Min\Router;

use Closure;

class Route
{
  protected string $path;
  protected array $middleware = [];
  protected string $method;

  public function __construct(
    string $path, 
    string $method, 
    string|array|Closure $callable, 
    array $middleware) {
    $this->path = $path;
    $this->method = $method;
    $this->$callable = $callable;
    $this->middleware = $middleware;
  }

  public function handle()
  {
  }

  public function getMiddleware()
  {
    return $this->middleware;
  }
}
