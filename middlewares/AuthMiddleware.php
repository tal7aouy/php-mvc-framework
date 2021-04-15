<?php


namespace app\app\middlewares;


use app\app\Application;
use app\app\exception\ForbiddenException;

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