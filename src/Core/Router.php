<?php

namespace App\Core;

class Router
{
    /** @var array<string, array<string, Route>> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): Route
    {
        $pattern = $this->convertPathToRegex($path);
        $route = new Route('GET', $path, $handler, $pattern);
        $this->routes['GET'][$path] = $route;
        return $route;
    }

    public function post(string $path, callable|array $handler): Route
    {
        $pattern = $this->convertPathToRegex($path);
        $route = new Route('POST', $path, $handler, $pattern);
        $this->routes['POST'][$path] = $route;
        return $route;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        // Limpiamos la URI de parámetros GET (?id=1)
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Eliminar trailing slash para evitar duplicidad de rutas (ej: /dashboard/ -> /dashboard)
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        // 1. Match exacto (rutas estáticas)
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $route->params = [];
            $this->executeRoute($route);
            return;
        }

        // 2. Match con regex (rutas dinámicas como /documents/{id}/edit)
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                if (preg_match($route->pattern, $uri, $matches)) {
                    // Filtrar solo named groups (parámetros)
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $route->params = $params;
                    $this->executeRoute($route);
                    return;
                }
            }
        }

        // 3. Manejo de error 404 (CORREGIDO: __DIR__ es src/Core, subimos un nivel a src/Views)
        http_response_code(404);
        $view404 = __DIR__ . '/../Views/404.php';
        
        if (file_exists($view404)) {
            require $view404;
        } else {
            echo '<div style="font-family:sans-serif; text-align:center; margin-top:50px;">
                    <h2>404 - Página no encontrada</h2>
                    <p>La ruta <strong>' . htmlspecialchars($uri) . '</strong> no está registrada en src/routes.php</p>
                  </div>';
        }
    }

    private function convertPathToRegex(string $path): string
    {
        $path = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '___PARAM_$1___', $path);
        $pattern = preg_quote($path, '#');
        $pattern = preg_replace('/___PARAM_([a-zA-Z_][a-zA-Z0-9_]*)___/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    private function executeRoute(Route $route): void
    {
        // Ejecutar middlewares antes del handler
        foreach ($route->middlewares as $middleware) {
            if (is_callable($middleware)) {
                $middleware();
            } elseif (is_array($middleware) && count($middleware) >= 1) {
                $callable = $middleware;
                if (count($middleware) === 2 && is_string($middleware[0])) {
                    $callable = [new $middleware[0](), $middleware[1]];
                }
                $callable();
            }
        }

        $handler = $route->handler;

        // Pasar parámetros al handler
        if (is_callable($handler)) {
            if ($route->params) {
                $handler(...array_values($route->params));
            } else {
                $handler();
            }
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            $instance = new $controller();
            if ($route->params) {
                $reflection = new \ReflectionMethod($controller, $method);
                $methodParams = $reflection->getParameters();
                $args = [];
                foreach ($methodParams as $param) {
                    $name = $param->getName();
                    if (isset($route->params[$name])) {
                        $args[] = $route->params[$name];
                    } elseif ($param->isOptional()) {
                        $args[] = $param->getDefaultValue();
                    }
                }
                $instance->$method(...$args);
            } else {
                $instance->$method();
            }
        }
    }
}

class Route
{
    public string $method;
    public string $path;
    public mixed $handler;
    public string $pattern = '';
    /** @var array<callable|array> */
    public array $middlewares = [];
    public array $params = [];

    public function __construct(string $method, string $path, mixed $handler, string $pattern = '')
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->pattern = $pattern;
    }

    public function middleware(mixed ...$middlewares): self
    {
        foreach ($middlewares as $mw) {
            if (is_array($mw) && isset($mw[0]) && !is_callable($mw)) {
                $this->middlewares = array_merge($this->middlewares, $mw);
            } else {
                $this->middlewares[] = $mw;
            }
        }
        return $this;
    }
}