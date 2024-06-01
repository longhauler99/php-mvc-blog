<?php

namespace App;

class Router
{
    protected array $routes = [];

    public function addRoute($route, $controller, $action, $method): void
    {
        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action): void
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

        foreach ($this->routes[$method] as $route => $data) {
            $routePattern = preg_replace('/{[^}]+}/', '([^/]+)', $route);
            $routePattern = str_replace('/', '\/', $routePattern);

            if (preg_match('/^' . $routePattern . '$/', $uri, $matches)) {
                array_shift($matches); // Remove the full match

                $controller = $data['controller'];
                $action = $data['action'];

                $config = require __DIR__ . '/../config.php';
                $controller = new $controller($config['db']);
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        throw new \Exception("No route found for URI: $uri");
    }
}

