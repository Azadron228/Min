<?php

namespace Min\Router;

class Route
{
  protected string $path;
  protected array $middleware = [];
  protected string $method;

  public function __construct(string $path, array $middleware, string $method)
  {
    $this->path = $path;
    $this->middleware = $middleware;
    $this->method = $method;

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
