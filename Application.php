<?php
namespace talhaouy\phpmvc;

use talhaouy\phpmvc\config\Database;

class Application
{
    public string $layout = 'master';
    public  Router $router;
    public  Request $request;
    public static string $ROOT_DIR;
    public Response $response;
    public Session $session;
    public static Application $app;
    public ?Controller $controller = null;
    public Database $data;
    public ?UserModel $user;
    public string $userClass = '';
    public View $view;

    public function __construct($rootPath,array $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->response = new Response();
        $this->session = new Session();
        $this->request = new Request();
        $this->view = new View();
        $this->router = new Router($this->request,$this->response);
        $this->data = new Database($config['db']);
        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = $this->userClass::KeyPrimary();
           $this->user =  $this->userClass::findFirst([$primaryKey=>$primaryValue]);
        }else{
            $this->user = null;
        }
    }



    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
    public function run()
    {
        try {
            echo $this->router->resolve();
        }catch (\Exception $ex){
            $this->response->setStatusCode($ex->getCode());
           echo $this->view->renderView('_error',[
               'exception'=>$ex
           ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->KeyPrimary();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
    public static function guest()
    {
        return !self::$app->user;
    }
}