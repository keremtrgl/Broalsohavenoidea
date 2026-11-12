<?php
declare(strict_types=1);

namespace App\Core;

/** Tiny regex router: "/post/{slug}" style patterns, per-route middleware. */
final class Router
{
    /** @var list<array{0:string,1:string,2:array{0:class-string,1:string},3:list<string>}> */
    private array $routes = [];

    /** @param array{0:class-string,1:string} $handler @param list<string> $mw */
    public function add(string $method, string $pattern, array $handler, array $mw = []): void
    {
        $this->routes[] = [$method, $pattern, $handler, $mw];
    }

    /** @return array{handler:array{0:class-string,1:string},params:array<string,string>,middleware:list<string>}|null */
    public function match(string $method, string $path): ?array
    {
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        foreach ($this->routes as [$m, $pattern, $handler, $mw]) {
            if ($m !== $method) {
                continue;
            }
            $regex = '#^' . preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/]+)', $pattern) . '$#u';
            if (preg_match($regex, $path, $mt)) {
                $params = array_filter($mt, 'is_string', ARRAY_FILTER_USE_KEY);
                return ['handler' => $handler, 'params' => $params, 'middleware' => $mw];
            }
        }
        return null;
    }
}
