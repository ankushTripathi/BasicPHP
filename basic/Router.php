<?php

namespace Basic;

class Router{
    
    protected $routes = [];
    protected $path;
    protected $methods = [];

    public function addRoute($uri,$handler,$method = 'GET'){
        $this->routes[$uri] = $handler;
        $this->methods[$uri] = $method;
    }

    public function setPath($uri = '/'){
        $this->path = $uri;
    }

    public function getResponse(){
        if($_SERVER['REQUEST_METHOD'] === $this->methods[$this->path]){
            return $this->routes[$this->path];
        }else{
            die('method not allowed');
        }
    }
}