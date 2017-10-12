<?php

namespace Basic;

use Basic\Exceptions\RouteNotFound;
use Basic\Exceptions\MethodNotAllowed;

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

        if(!isset($this->routes[$this->path]))
            throw new RouteNotFound;

        if(in_array($_SERVER['REQUEST_METHOD'],array_keys($this->routes[$this->path]))){
            return $this->routes[$this->path][$_SERVER['REQUEST_METHOD']];
        }else{
            throw new MethodNotAllowed;
        }
    }
}