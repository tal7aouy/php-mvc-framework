<?php
namespace talhaouy\phpmvc;


use talhaouy\phpmvc\exception\NotFoundException;
use talhaouy\phpmvc\middlewares\BaseMiddleware;

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
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path,$callback){
        return $this->routes['get'][$path] = $callback;
    }
      public function post($path,$callback){
            return $this->routes['post'][$path] = $callback;
        }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false){

            throw new NotFoundException();
        }
        if(is_string($callback)){
            return  Application::$app->view->renderView($callback);
        }
        if(is_array($callback)){
            /**@var Controller $controller*/
            $controller = new $callback[0]();
           Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] =  $controller;
            /**@var BaseMiddleware $middleware */
            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->run();
            }
        }
        return call_user_func($callback,$this->request,$this->response);

    }

}