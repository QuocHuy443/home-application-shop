<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $prefix = '';
    private $middlewares = [];

    public function get($uri, $action, $middleware = [])
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post($uri, $action, $middleware = [])
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function group($prefix, $middleware, $callback)
    {
        $previousPrefix = $this->prefix;
        $previousMiddlewares = $this->middlewares;

        $this->prefix .= $prefix;
        $this->middlewares = array_merge($this->middlewares, $middleware);

        $callback($this);

        $this->prefix = $previousPrefix;
        $this->middlewares = $previousMiddlewares;
    }

    private function addRoute($method, $uri, $action, $middleware = [])
    {
        $uri = $this->prefix . $uri;
        $middleware = array_merge($this->middlewares, $middleware);
        $this->routes[] = compact('method', 'uri', 'action', 'middleware');
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            // Convert route uri like /product/{slug} to regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                // Filter out numeric keys from matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Execute middleware
                foreach ($route['middleware'] as $mw) {
                    $this->executeMiddleware($mw);
                }

                // Execute action
                $controller = new $route['action'][0]();
                $methodName = $route['action'][1];
                return call_user_func_array([$controller, $methodName], $params);
            }
        }

        // 404
        http_response_code(404);
        echo "<h1>404 - Trang không tồn tại!</h1>";
        exit;
    }

    private function executeMiddleware($name)
    {
        $middlewares = [
            'auth' => \App\Middleware\AuthMiddleware::class,
            'admin' => \App\Middleware\AdminMiddleware::class,
        ];

        if (isset($middlewares[$name])) {
            $middlewares[$name]::handle();
        }
    }
}
