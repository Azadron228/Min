<?php

declare(strict_types=1);

namespace Min\Router;

interface RouterInterface
{
    public function addRoute(Route $route): void;
}
