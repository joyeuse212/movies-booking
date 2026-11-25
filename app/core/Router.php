<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    // Register a GET route
    public function get(string $uri, callable $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    // Register a POST route
    public function post(string $uri, callable $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    // Dispatch the request
    public function dispatch(string $uri, string $method): void
    {
        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
            return;
        }

        http_response_code(404);
        echo '404 - Page Not Found';
  }
}
