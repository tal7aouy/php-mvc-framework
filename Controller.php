<?php


namespace talhaouy\phpmvc;
use talhaouy\phpmvc\Application;
use talhaouy\phpmvc\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = "master";
    /**
     * @var \talhaouy\phpmvc\middlewares\BaseMiddleware[]
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