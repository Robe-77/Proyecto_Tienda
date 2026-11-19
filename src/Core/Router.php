<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    /** @var array<string, array<string, array{0: class-string, 1: string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function match(array $methods, string $path, array $handler): void
    {
        foreach ($methods as $method) {
            $upper = strtoupper($method);
            $this->routes[$upper][$this->normalize($path)] = $handler;
        }
    }

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        $base = appBasePath();
        if ($base !== '' && str_starts_with($uriPath, $base)) {
            $uriPath = substr($uriPath, strlen($base));
        }

        $path = $this->normalize($uriPath);

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo '<h1>404 - Página no encontrada</h1>';
            return;
        }

        [$controllerClass, $action] = $this->routes[$method][$path];
        $controller = new $controllerClass();
        $controller->$action();
    }

    private function normalize(string $path): string
    {
        $trimmed = '/' . trim($path, '/');
        return $trimmed === '' ? '/' : $trimmed;
    }
}
