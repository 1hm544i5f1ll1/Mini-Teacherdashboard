<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $callback, $middlewares = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function get($path, $callback, $middlewares = []) {
        $this->add('GET', $path, $callback, $middlewares);
    }

    public function post($path, $callback, $middlewares = []) {
        $this->add('POST', $path, $callback, $middlewares);
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Normalize URI relative to public root
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptPath !== '/' && strpos($uri, $scriptPath) === 0) {
            $uri = substr($uri, strlen($scriptPath));
        }
        $uri = '/' . trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->match($route['path'], $uri)) {
                // Check CSRF on POST
                if ($method === 'POST') {
                    Csrf::verify();
                }

                // Run middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    if (!$middlewareInstance->handle()) {
                        return;
                    }
                }

                $callback = $route['callback'];
                if (is_array($callback)) {
                    $controller = new $callback[0]();
                    $action = $callback[1];
                    $controller->$action();
                } else {
                    $callback();
                }
                return;
            }
        }

        // 404
        http_response_code(404);
        echo "404 Page Not Found";
    }

    private function match($routePath, $uri) {
        $routePath = '/' . trim($routePath, '/');
        return $routePath === $uri;
    }
}
