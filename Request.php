<?php


namespace talhaouy\phpmvc;


class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path,'?');
        if($position === false){
            return $path;
        }
        return substr($path,0,$position);
        
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet()
    {
        return $this->method() === 'get';
    }
 public function isPost()
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {
        $body = [];
       if($this->method() === "get"){
           foreach ($_GET as $key=>$value){
               $body[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
           }
       }
        if($this->method() === "post"){
            foreach ($_POST as $key=>$value){
                $body[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
    /**
     * Get json as associative array or object from request body
     * Content-Type header should be application/json in HTTP POST
     * @param boolean $associative When true, JSON objects will be returned as associative arrays; when false, JSON objects will be returned as objects.
     * @param integer $flags 
     * @param integer $depth Maximum nesting depth of the structure being decoded.
     * @return mixed
     */
    public function body(bool $associative = true, int $flags = 0, int $depth = 512)
    {
        $body = file_get_contents("php://input");
        $object = json_decode($body, $associative, $depth, $flags);
        return $object;
    }
}