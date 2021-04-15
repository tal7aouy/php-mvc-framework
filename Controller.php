<?php


namespace app\app;
use app\app\Application;
use app\app\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = "master";
    /**
     * @var \app\app\middlewares\BaseMiddleware[]
     **/
    protected array $middlewares = [];
    public string $action = '';
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    public function view($view,$params = [])
    {
        return Application::$app->view->renderView($view,$params);
    }

    public function registerMiddleware(BaseMiddleware $baseMiddleware)
    {
        $this->middlewares[] = $baseMiddleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}