<?php
class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void {
        $this->routes[] = ['GET', $path, $controller, $method];
    }

    public function post(string $path, string $controller, string $method): void {
        $this->routes[] = ['POST', $path, $controller, $method];
    }

    public function dispatch(string $uri, string $httpMethod): void {
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $routePath, $controllerName, $action]) {
            $pattern = $this->buildPattern($routePath);
            if ($routeMethod !== $httpMethod) continue;
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->runController($controllerName, $action, $params);
                return;
            }
        }
    }

    private function buildPattern(string $path): string {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function runController(string $controllerName, string $action, array $params): void {
        $parts = explode('\\', $controllerName);
        $file  = __DIR__ . '/../app/Controllers/' . str_replace('\\', '/', $controllerName) . '.php';

        require_once $file;
        $shortName = end($parts);
        $ctrl = new $shortName();

        if (empty($params)) {
            $ctrl->$action();
        } else {
            $ctrl->$action($params);
        }
    }
}
