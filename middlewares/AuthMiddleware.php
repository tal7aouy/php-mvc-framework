<?php


namespace talhaouy\phpmvc\middlewares;


use talhaouy\phpmvc\Application;
use talhaouy\phpmvc\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * AuthMiddleware constructor.
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    public function run()
    {
       if(Application::guest()){
           if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)){
                throw  new ForbiddenException();
           }
       }
    }
}