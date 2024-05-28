<?php

namespace App;

class Router {
    protected array $routes = [];

    public function addRoute($route, $controller, $action, $method): void
    {
        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action): void
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    /**
     * @throws \Exception
     */
    public function dispatch(): void
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];

        if(array_key_exists($uri, $this->routes[$method]))
        {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];

            $config = require __DIR__ . '/../config.php';
            $controller = new $controller($config['db']);
            $controller->$action();
        }
        else
        {
            throw new \Exception("No route found for URI: $uri");
        }
    }
}