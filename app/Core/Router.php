<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $currentMiddleware = [];

    /**
     * GET route
     */
    public function get(string $uri, string $action): self
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * POST route
     */
    public function post(string $uri, string $action): self
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * PUT route
     */
    public function put(string $uri, string $action): self
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * DELETE route
     */
    public function delete(string $uri, string $action): self
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Route ekle
     */
    private function addRoute(string $method, string $uri, string $action): self
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => '/' . trim($uri, '/'),
            'action' => $action,
            'middleware' => $this->currentMiddleware,
        ];
        return $this;
    }

    /**
     * Son route'a middleware ekle
     */
    public function middleware(string|array $middleware): self
    {
        if (!empty($this->routes)) {
            $lastKey = array_key_last($this->routes);
            $middlewares = is_array($middleware) ? $middleware : [$middleware];
            $this->routes[$lastKey]['middleware'] = array_merge(
                $this->routes[$lastKey]['middleware'],
                $middlewares
            );
        }
        return $this;
    }

    /**
     * Middleware grubu
     */
    public function group(array $middleware, callable $callback): void
    {
        $previousMiddleware = $this->currentMiddleware;
        $this->currentMiddleware = array_merge($this->currentMiddleware, $middleware);
        $callback($this);
        $this->currentMiddleware = $previousMiddleware;
    }

    /**
     * Request'i işle
     */
    public function dispatch(): void
    {
        $method = $this->getMethod();
        $uri = $this->getUri();

        foreach ($this->routes as $route) {
            $params = $this->match($route['uri'], $uri);

            if ($params !== false && $route['method'] === $method) {
                $this->runMiddleware($route['middleware']);
                $this->runAction($route['action'], $params);
                return;
            }
        }

        // 404
        (new Response())->error(404, 'Sayfa bulunamadı');
    }

    /**
     * URI eşleştir
     */
    private function match(string $routeUri, string $requestUri): array|false
    {
        // Tam eşleşme
        if ($routeUri === $requestUri) {
            return [];
        }

        // {param} pattern
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }

    /**
     * HTTP method al
     */
    private function getMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper($_POST['_method']);
            if (in_array($spoofed, ['PUT', 'DELETE'])) {
                return $spoofed;
            }
        }

        return $method;
    }

    /**
     * URI al
     */
    private function getUri(): string
    {
        $uri = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = '/' . trim($uri ?? '', '/');

        return $uri === '' ? '/' : $uri;
    }

    /**
     * Middleware çalıştır
     */
    private function runMiddleware(array $middlewares): void
    {
        $aliases = [
            'auth' => \App\Middleware\AuthMiddleware::class,
            'guest' => \App\Middleware\GuestMiddleware::class,
            'csrf' => \App\Middleware\CsrfMiddleware::class,
            'role' => \App\Middleware\RoleMiddleware::class,
        ];

        foreach ($middlewares as $name) {
            $params = [];
            if (str_contains($name, ':')) {
                [$name, $paramStr] = explode(':', $name, 2);
                $params = explode(',', $paramStr);
            }

            $class = $aliases[$name] ?? $name;

            if (class_exists($class)) {
                $result = (new $class())->handle($params);
                if ($result === false) {
                    exit;
                }
            }
        }
    }

    /**
     * Controller action çalıştır
     */
    private function runAction(string $action, array $params): void
    {
        [$controller, $method] = explode('@', $action);
        $class = "App\\Controllers\\{$controller}";

        if (class_exists($class) && method_exists($class, $method)) {
            call_user_func_array([new $class(), $method], $params);
        } else {
            throw new \RuntimeException("Action bulunamadı: {$action}");
        }
    }
}