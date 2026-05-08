<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$this->normalize($uri)] = $action;
    }
    public function post($uri, $action)
    {
        $this->routes['POST'][$this->normalize($uri)] = $action;
    }



    protected function normalize($uri)
    {
        return '/' . trim($uri, '/');
    }

    public function dispatch($uri)
    {
        $uri = $this->normalize($uri);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        if (isset($this->routes[$method][$uri])) {
            $this->callAction($this->routes[$method][$uri]);
            return;
        }

        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('/\(:any\)/', '([^/]+)', $route);
            $pattern = preg_replace('/\(:num\)/', '([0-9]+)', $pattern);
            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                array_shift($matches);
                $this->callAction($action, $matches);
                return;
            }
        }

        $this->errorPage(404);
    }


    protected function callAction($action, array $params = [])
    {
        if ($action instanceof \Closure) {
            $action(...$params);
            return;
        }

        [$controller, $method] = explode('@', $action);
        $controller = "App\\Controllers\\" . str_replace('/', '\\', $controller);

        if (class_exists($controller)) {
            $obj = new $controller();
            if (method_exists($obj, $method)) {
                $obj->$method(...$params);
                return;
            }
        }

        $this->errorPage(500);
    }

    protected function errorPage(int $code)
    {
        $controller = new \App\Controllers\Errors\ErrorsController();

        switch ($code) {
            case 404:
                $controller->error404();
                break;
            case 500:
                $controller->error500();
                break;
            case 503:
                $controller->maintenance();
                break;
            default:
                http_response_code($code);
                echo "{$code} Error";
                break;
        }

        exit;
    }
}
