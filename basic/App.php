<?php

namespace Basic;

class App{

    protected $container;
    protected $router;
    public function __construct(){
        $this->container = new Container([
            'router' => function(){
                return new Router;
            }
        ]);
        $this->router = $this->container->router;
    }

    public function getContainer(){
        return $this->container;
    }

    public function configure($mode){
        switch($mode){
            case 'development' : $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents('config/development.json', FILE_USE_INCLUDE_PATH),
                                        true
                                        );
                                };
                                break;
            case 'production'  :  $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents('config/production.json', FILE_USE_INCLUDE_PATH),
                                        true
                                        );
                                };
                                break;
            default : $this->break('app incorrectly configured!');

        }
    }

    public function get($uri,$handler){
        if(is_array($handler)){
            if(count($handler) === 2){
                $class = $handler[0];
                $method_name = $handler[1];
                $obj = new $class();
                $handler = $obj->{$method_name}();
            }
        }
        $this->router->addRoute($uri,$handler);
    }

    public function post($uri,$handler){
        if(is_array($handler)){
            if(count($handler) === 2){
                $class = $handler[0];
                $method_name = $handler[1];
                $obj = new $class();
                $handler = $obj->{$method_name}();
            }
        }
        $this->router->addRoute($uri,$handler,'POST');
    }

    public function put($uri,$handler){
        if(is_array($handler)){
            if(count($handler) === 2){
                $class = $handler[0];
                $method_name = $handler[1];
                $obj = new $class();
                $handler = $obj->{$method_name}();
            }
        }
        $this->router->addRoute($uri,$handler,'PUT');
    }
    
    public function delete($uri,$handler){
        if(is_array($handler)){
            if(count($handler) === 2){
                $class = $handler[0];
                $method_name = $handler[1];
                $obj = new $class();
                $handler = $obj->{$method_name}();
            }
        }
        $this->router->addRoute($uri,$handler,'DELETE');
    }

    public function route($uri,$class){
        $object = new $class();
        $this->get($uri,$object->show());
        $this->post($uri,$object->store());
        $this->put($uri,$object->update());
        $this->delete($uri,$object->remove());
    }

    public function run(){
        $this->router->setPath($_SERVER['PATH_INFO'] ?? '/');
        $response = $this->router->getResponse();
        $this->process($response);
    }

    protected function process($callback){
        return $callback();
    }

    public function break($msg){
        die($msg);
    }

}