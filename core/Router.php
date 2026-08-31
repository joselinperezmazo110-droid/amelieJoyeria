<?php
class Router {
    protected $routes = [];

    public function add($route, $callback) { $this->routes[$route] = $callback; }

    public function dispatch($uri) {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $uri = preg_replace('#/index(?:\.php)?$#i', '', $uri);
        $uri = preg_replace('#^/(?:.+?/)?public#i', '', $uri);

        $basePatterns = [
            '/Proyecto/amelieJoyeria',
            '/amelieJoyeria',
            '/proyecto/ameliejoyeria',
            '/Proyecto',
            '/proyecto',
            '/amelieJoyeria',
            ''
        ];

        foreach ($basePatterns as $base) {
            if ($base !== '' && stripos($uri, $base) === 0) {
                $uri = substr($uri, strlen($base));
                break;
            }
        }

        $uri = trim($uri, '/');

        if ($uri === '') { $uri = 'home'; }

        $routeAliases = [
            'home' => ['HomeController', 'index'],
            'index' => ['HomeController', 'index'],
            'productos' => ['ProductController', 'index'],
            'contacto' => ['ContactController', 'index'],
            'login' => ['AuthController', 'login'],
            'register' => ['AuthController', 'register'],
            'logout' => ['AuthController', 'logout'],
        ];

        if (isset($routeAliases[$uri])) {
            [$controllerName, $action] = $routeAliases[$uri];
        } else {
            $parts = explode('/', $uri);
            $controllerName = ucfirst(array_shift($parts)) . 'Controller';
            $action = array_shift($parts) ?? 'index';
        }

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

        call_user_func_array([$controller, $action], []);
    }
}
