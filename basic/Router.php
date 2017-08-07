<?php

namespace Basic;

class Router{
    
    protected $routes = [];
    protected $path;

    public function addRoute($uri,$handler,$method = 'GET'){
        if(empty($this->routes[$uri]))
            $this->routes[$uri] = [];
        $this->routes[$uri][$method] = $handler;
    }

    public function setPath($uri = '/'){
        $this->path = $uri;
    }

    public function getResponse(){
        if(in_array($_SERVER['REQUEST_METHOD'],array_keys($this->routes[$this->path]))){
            return $this->routes[$this->path][$_SERVER['REQUEST_METHOD']];
        }else{
            die('method not allowed');
        }
    }
}