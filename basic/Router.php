<?php

namespace Basic;

class Router{
    
    protected $routes = [];
    protected $path;

    public function addRoute($uri,$handler){
        $this->routes[$uri] = $handler;
    }

    public function setPath($uri = '/'){
        $this->path = $uri;
    }

    public function getResponse(){
        return $this->routes[$this->path];
    }

}