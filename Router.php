<?php

namespace talhaouy\phpmvc;


use talhaouy\phpmvc\exception\NotFoundException;
use talhaouy\phpmvc\middlewares\BaseMiddleware;
use talhaouy\phpmvc\exception\MethodNotAllowedException;

class Router
{
    public array $routes = [];
    public  Request $request;
    public Response $response;

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, $callback)
    {
        return $this->routes['get'][$path] = $callback;
    }
    public function post(string $path, $callback)
    {
        return $this->routes['post'][$path] = $callback;
    }

    /**
     * Find a route with the given request URI.
     *
     * @param string $uri the request URI
     * @param string|null $givenMethod the method (optional)
     * @return array|null
     */
    public function getRoute(string $uri, string $givenMethod = null)
    {
        foreach ($this->routes as $method => $map) {
            if ((isset($map[$uri]) && $givenMethod === null) || (isset($map[$uri]) && $method === $givenMethod)) {
                return [
                    "method" => $method,
                    "route" => $uri,
                    "action" => $map[$uri],
                ];
            }
        }

        return null;
    }


    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        $routeAny = $this->getRoute($path);
        $route = $this->getRoute($path, $method);

        if ($route === null && $routeAny !== null) {
            throw new MethodNotAllowedException();
        }

        $callback = $route["action"] ?? false;

        if ($callback === false) {

            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return  Application::$app->view->renderView($callback);
        }
        if (is_array($callback)) {
            /**@var Controller $controller*/
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] =  $controller;
            /**@var BaseMiddleware $middleware */
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->run();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}
