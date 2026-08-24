<?php
class Router {
    protected $routes = [];

    public function add($route, $callback) { $this->routes[$route] = $callback; }

    public function dispatch($uri) {
        $uri = strtok(parse_url($uri, PHP_URL_PATH), '?');
        $uri = trim($uri, '/');
        if ($uri === '') { $uri = 'home/index'; }
        $parts = explode('/', $uri);
        $controllerName = ucfirst(array_shift($parts)) . 'Controller';
        $action = array_shift($parts) ?? 'index';
        $params = $parts;

        if (!class_exists($controllerName)) {
            header("HTTP/1.0 404 Not Found");
            echo "Controlador $controllerName no encontrado";
            return;
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $action)) {
            header("HTTP/1.0 404 Not Found");
            echo "Acción $action no encontrada";
            return;
        }

        call_user_func_array([$controller, $action], $params);
    }
}
